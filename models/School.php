<?php


class School {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function generateId ($length = 10) {
        $id = "sch_";

        for ($i = 0; $i < $length; $i++) { 
            $id .= rand(0, 9);
        }

        return $id;
    }

    public function createSchool(array $school)
    {
        extract($school);

        try {
            $id = $this->generateId();
            $schoolPlan = $plan ? $plan : "basic";


            $query = "INSERT INTO `school_table`(`school_name`, `school_logo`, `school_id`, `school_slug`, `plan`, `school_email`, `password`) VALUES (:school_name, :school_logo, :school_id, :school_slug, :plan, :school_email, :school_password)";
            $result = $this->connection->prepare($query);
            $result->execute([
                'school_name' => $schoolName,
                'school_logo' => $schoolLogo,
                'school_id' => $id,
                'school_slug' => $schoolSlug,
                'plan' => $schoolPlan,
                'school_email' => $schoolEmail,
                'school_password' => $password,
            ]);

            if($result){
                $data = $this->getSchool ($id);

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

    public function getAllSchools () 
    {
        try{
            $query = "SELECT * FROM `school_table`";
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

    public function getSchool ($id) 
    {
        try{
            $query = "SELECT * FROM `school_table` WHERE `school_id` = ?";
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

    public function deleteSchool($id) 
    {
        $school = $this->getSchool($id);

        if($school['status'] === 200){
            try {
                $query = "DELETE FROM `school_table` WHERE `school_id` = ?";
                $result = $this->connection->prepare($query);
                $result->execute([$id]);

                if($result){
                    $response = [
                        'status' => 200,
                        'data' => $school['data'],
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

    public function updateSchool ($id, array $school)
    {
        extract($school);
        try {
            $query = "UPDATE `school_table` SET `school_name`= :school_name, `school_logo` = :school_logo, `school_slug` = :school_slug, `plan` = :plan, `school_email` = :school_email WHERE `school_id` = :school_id";
            $result = $this->connection->prepare($query);
            $result->execute([
                'school_name' => $schoolName,
                'school_logo' => $schoolLogo,
                'school_slug' => $schoolSlug,
                'plan' => $plan,
                'school_email' => $schoolEmail,
                'school_id' => $id,
            ]);

            if($result){
                $data = $this->getSchool ($id);

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