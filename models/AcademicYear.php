<?php

class AcademicYear {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function generateId($length = 12) 
    {
        $id = "aca_";

        for ($i = 0; $i < $length; $i++) {
            $id .= rand(0, 9);
        }

        return $id;
    }

    public function getAcademicYear($schoolId)
    {
        try {
            $sql = "SELECT * FROM `academic_year` WHERE `school_id` = ? AND `is_current` = ?";
            $result = $this->connection->prepare($sql);
            $result->execute([$schoolId, 1]);
    
            $data = $result->fetch();
    
            $response = [
                "status" => 200,
                "data" => $data,
                "error" => null
            ];
    
            return $response;
        }
        catch (PDOException $e) {
            $response = [
                "status" => 500,
                "data" => null,
                "error" => $e->getMessage()
            ];
    
            return $response;
        }

    }

    public function deleteAcademicYear($id)
    {
        try {
            $sql = "SELECT * FROM `academic_year` WHERE `id` = ?";
            $result = $this->connection->prepare($sql);
            $result->execute([$id]);
    
            $data = $result->fetch();
    
            $response = [
                "status" => 200,
                "data" => $data,
                "error" => null
            ];
    
            return $response;
        }
        catch (PDOException $e) {
            $response = [
                "status" => 500,
                "data" => null,
                "error" => $e->getMessage()
            ];
    
            return $response;
        }

    }

    public function updateAcademicYear ($id, $schoolId) 
    {
        try {
            // Check if a year exists in the database
            $isAcademicYear = $this->getAcademicYear($schoolId);
    
            if($isAcademicYear['status'] == 200) {
                $currentAcademicId = $isAcademicYear['data']['id'];

                // Set is_current to false in that year
                $query = "UPDATE `academic_year` SET `is_current` = ? WHERE `school_id` = ? AND `id` = ?";
                $result = $this->connection->prepare($query);
                $result->execute([0, $schoolId, $currentAcademicId]);

                if($result) {
                    // Set is_current of that given year to true
                    $query = "UPDATE `academic_year` SET `is_current` = ? WHERE `school_id` = ? AND `id` = ?";
                    $result = $this->connection->prepare($query);
                    $result->execute([1, $schoolId, $id]);

                    if($result) {
                        $data = $this->getAcademicYear($schoolId);

                        $response = [
                            "data" => $data,
                            "error" => null,
                            "status" => 201
                        ];
                    }
                }
            }
            else {

                // Set is_current of that given year to true
                $query = "UPDATE `academic_year` SET `is_current` = ? WHERE `school_id` = ? AND `id` = ?";
                $result = $this->connection->prepare($query);
                $result->execute([1, $schoolId, $id]);

                if($result) {
                    $data = $this->getAcademicYear($schoolId);

                    $response = [
                        "data" => $data,
                        "error" => null,
                        "status" => 201
                    ];
                }
            }
        }
        catch (PDOException $e) {
            $response = [
                "data" => null,
                "error" => $e->getMessage(),
                "status" => 500
            ];

            return $response;
        }
    }
    
    public function createAcademicYear(array $academic)
    {
        extract($academic);

        try {
            // Check if a year exists in the database
            $isAcademicYear = $this->getAcademicYear($schoolId);

            if($isAcademicYear['status'] == 200){
                // Update the academic year
                $query = "UPDATE `academic_year` SET `year` = :year WHERE `school_id` = :schoolId";  
            }
            else {
                // Insert an academic year
                $query = "INSERT INTO `academic_year` (`year`, `school_id`) VALUES (:year, :schoolId)";   
            }

            $result = $this->connection->prepare($query);
            $result->execute([
                "year" => $year,
                "schoolId" => $schoolId
            ]); 

            if($result) {
                $data = $this->getAcademicYear($schoolId);
                return $data;
            }
            else {
                throw new Exception("No academic year set");
            }
        }
        catch (PDOException $e) {
            $response = [
                "status" => 500,
                "data" => null,
                "error" => $e->getMessage()
            ];

            return $response;
        }
    }
}