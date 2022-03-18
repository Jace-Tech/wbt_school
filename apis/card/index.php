<?php 


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require("../../db/config.php");
require("../../models/Card.php");
require("../../utils/index.php");


$cards = new Card($conn);


if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET['id'])) {
        $result = $cards->getAllCards();
        
        echo json_encode($result);
        die();
    }

    else {
        $id = $_GET['id'];
        $result = $cards->getCard($id);
        
        echo json_encode($result);
        die();
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_GET['id'])) {
        $schoolId = clean($_POST['schoolId']);

        $result = $cards->createCard($schoolId);
        echo json_encode($result);
        die();
    }

    else {
        $id = $_GET['id'];

        $result = $cards->updateCard($id);
        echo json_encode($result);
        die();
    }
}
