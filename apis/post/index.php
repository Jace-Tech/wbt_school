<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require("../../db/config.php");
require("../../models/Post.php");
require("../../utils/index.php");

$posts = new Post($conn);

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET['id'])) {
        $response = $posts->getAllPosts();

        echo json_encode($response);
        die();
    }

    else {
        $response = $posts->getPost($_GET['id']);

        echo json_encode($response);
        die();
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_GET['id'])) {
        $schoolId = clean($_POST['schoolId']);
        $title = clean($_POST['title']);
        $content = clean($_POST['content']);
        $slug = getSlug($title);
        
        if($_FILES['image']) {
            $imageResult = processImage("image", $slug, "post");
            $newPost = [
                "schoolId" => $schoolId,
                "title" => $title,
                "content" => $content,
                "image" => $imageResult['imagePath']
            ];

            if($imageResult['status']){
                $response = $posts->createPost($newPost);

                echo json_encode($response);
                die();
            }
        }
    }

    else {
        $id = $_GET['id'];

        $oldPost = $posts->getPost($id)['data'];

        $schoolId = clean($_POST['schoolId']);
        $title = clean($_POST['title']);
        $content = clean($_POST['content']);
        $slug = getSlug($title);
        
        
        if($_FILES['image']) {
            $imagePath = getLogoPath($oldPost['image']);
            $oldImage = $imagePath['logo'];
            $deleted = unlink("../../uploads/post/$oldImage");

            if($deleted){
                $imageResult = processImage("image", $slug, "post");
                $newPost = [
                    "schoolId" => $schoolId,
                    "title" => $title,
                    "content" => $content,
                    "image" => $imageResult['imagePath']
                ];
    
                if($imageResult['status']){
                    $response = $posts->updatePost($id, $newPost);
                    echo json_encode($response);
                    die();
                }
            }
            else {
                echo json_encode([
                    "status" => 500,
                    "error" => "Error deleting image",
                    "data" => [
                        'oldPost' => $oldPost,
                        'imagePath' => $imagePath,
                    ]
                ]);
            }
        }
        else {
            $newPost = [
                "schoolId" => $schoolId,
                "title" => $title,
                "content" => $content,
            ];

            $response = $posts->updatePost($id, $newPost);
            echo json_encode($response);
            die();
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $id = $_GET['id'];

    $oldPost = $posts->getPost($id)['data'];
    $imagePath = getLogoPath($oldPost['image']);

    $oldImage = $imagePath['logo'];
    $folder = $imagePath['folder'];

    $deleteImage = unlink("../../uploads/post/{$oldImage}");

    if($deleteImage) {
        $response = $posts->deletePost($id);
        echo json_encode($response);
    }

    else {
        echo json_encode([
            "status" => 500,
            "error" => "Error deleting image",
            "data" => [
                'oldPost' => $oldPost,
                'imagePath' => $imagePath
            ]
        ]);
    }
    die();
}