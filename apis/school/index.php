<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require("../../db/config.php");
require("../../models/School.php");
require("../../utils/index.php");


$school = new School($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!isset($_GET['id'])) {
        $schoolEmail = clean($_POST['schoolEmail']);
        $schoolName = clean($_POST['schoolName']);
        $schoolSlug = $_POST['schoolSlug'] ? clean($_POST['schoolSlug']) : getSlug($_POST['schoolName']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
        if($_FILES['schoolLogo']){
            $imageResult = processImage("schoolLogo", $schoolSlug, "logo");
            
        
            if($imageResult['status']){
                $newSchool = [
                    'schoolName' => $schoolName,
                    'schoolEmail' => $schoolEmail,
                    'schoolSlug' => $schoolSlug,
                    'password' => $password,
                    'schoolLogo' => $imageResult['imagePath'],
                ];

                $response = $school->createSchool($newSchool);

                echo json_encode($response);
                die();
            }
        }
    }
    else {
        $id = $_GET['id'];
        $oldSchool = $school->getSchool($id)['data'];

        $schoolEmail = clean($_POST['schoolEmail']);
        $schoolName = clean($_POST['schoolName']);
        $schoolSlug = $_POST['schoolSlug'] ? clean($_POST['schoolSlug']) : getSlug($_POST['schoolName']);

        $oldLogo = getLogoPath($oldSchool['SchoolLogo'])['logo'];

        if($_FILES['schoolLogo']){
            $imageResult = processImage("schoolLoge", $schoolSlug, "logo");
            $deleted = unlink("../../uploads/logo/$oldLogo");

            if($deleted){
                $move = move_uploaded_file($file, $destination.$imageResult['imageName']);
            }
        }
        
        $newSchool = [
            'schoolName' => $schoolName,
            'schoolEmail' => $schoolEmail,
            'schoolSlug' => $schoolSlug,
            'schoolLogo' => $imageResult['imagePath'],
        ];

        $response = $school->updateSchool($id, $newSchool);

        echo json_encode($response);
        die();
    }

}

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $id = $_GET['delete'];

    $response = $school->deleteSchool($id);
    echo json_encode([$response]);
    die();
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    if(isset($_GET['id']) && !empty($_GET['id'])){
        $id = $_GET['id'];
        $response = $school->getSchool($id);

        echo json_encode($response);
        die();
    }

    else {
        $response = $school->getAllSchools();
        echo json_encode($response);
        die();
    }

}