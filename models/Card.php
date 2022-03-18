<?php


class Card {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function generateId ($length = 10) {
        $id = "crd_";

        for ($i = 0; $i < $length; $i++) { 
            $id .= rand(0, 9);
        }

        return $id;
    }

    public function generateCardPin ($length = 16   ) {
        $pin = "";

        for ($i = 0; $i < $length; $i++) { 
            if(($i % 4 == 0) && ($i != 0)){
                $pin .= "-";
            }
            $pin .= rand(0, 9);
        }

        return $pin;
    }

    public function createCard($schoolId)
    {

        try {
            $id = $this->generateId();
            $pin = $this->generateCardPin();
            $is_used = 0;

            $query = "INSERT INTO `card_table`(`school_id`, `card_id`, `card_pin`, `is_used`) VALUES (:school_id, :card_id, :card_pin, :is_used)";
            $result = $this->connection->prepare($query);
            $result->execute([
                'school_id' => $schoolId,
                'card_id' => $id,
                'card_pin' => $pin,
                'is_used' => $is_used,
            ]);

            if($result){
                $data = $this->getCard($id);

                $response = [
                    'status' => 201,
                    'data' => $data['data'],
                    'error' => null
                ];

                return $response;
            }

        }

        catch (PDOException $e) {
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }

    }

    public function getAllCards () 
    {
        try{
            $query = "SELECT * FROM `card_table`";
            $result = $this->connection->prepare($query);
            $result->execute();

            if($result->rowCount()){
                $response = [
                    'status' => 200,
                    'data' => $result->fetchAll(),
                    'error' => null
                ];

                return $response;
            }
        }

        catch (PDOException $e){
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }
        
    }

    public function getCard ($id) 
    {
        try{
            $query = "SELECT * FROM `card_table` WHERE `card_id` = ?";
            $result = $this->connection->prepare($query);
            $result->execute([$id]);

            if($result->rowCount()){
                $response = [
                    'status' => 200,
                    'data' => $result->fetch(),
                    'error' => null
                ];

                return $response;
            }
        }

        catch (PDOException $e){
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }
        
    }

    public function deletecard($id) 
    {
        $card = $this->getcard($id);

        if($card['status'] === 200){
            try {
                $query = "DELETE FROM `card_table` WHERE `card_id` = ?";
                $result = $this->connection->prepare($query);
                $result->execute([$id]);

                if($result){
                    $response = [
                        'status' => 200,
                        'data' => $card['data'],
                        'error' => null
                    ];

                    return $response;
                }
            } 
            catch (PDOException $e) {
                $response = [
                    'status' => 500,
                    'data' => null,
                    'error' => $e->getMessage(),
                ];

                return $response;
            }
            

        }
    }

    public function updateCard ($id)
    {
        try {
            $is_used = true;

            $query = "UPDATE `card_table` SET `is_used` = :isUsed WHERE `card_id` = :card_id";
            $result = $this->connection->prepare($query);
            $result->execute([
                'isUsed' => $is_used,
                'card_id' => $id
            ]);

            if($result){
                $data = $this->getcard ($id);

                if($data['status'] == 200){
                    $response = [
                        'status' => 200,
                        'data' => $data['data'],
                        'error' => null
                    ];

                    return $response;
                }
            }

            
        } 
        
        catch (PDOException $e) {
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }
        
    }
}