<?php  

function clean($input)
{
    return htmlspecialchars(trim($input));
}

function getAbsolutePath($path)
{
    return "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}/$path";
}

function getSlug($name) {
    $name = explode(' ', $name);
    $slug = strtolower(join('-', $name));

    return $slug;
}

function getLogoPath ($logo) {
    $path = explode('/', $logo);
    $logo = $path[count($path) - 1];
    $folder = $path[count($path) - 2];

    return [
        'logo' => $logo,
        'folder' => $folder
    ];
}


function processImage($file_input, $slug, $path)
{
    $allowed = ['image/png'];
    $type = $_FILES[$file_input]['type'];

    if(!in_array($type, $allowed)) {
        echo json_encode([
            'status' => 400,
            'message' => "Only PNG image allowed",
            'error' => true,
            'file' => $_FILES
        ]);

        die();
    };


    $filename = $_FILES[$file_input]['name'];
    $destination = "../../uploads/$path/";
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $time = time();

    $newfilename = "$slug-image-$time.$ext";
    $file = $_FILES[$file_input]['tmp_name'];
    $move = move_uploaded_file($file, $destination.$newfilename);

    $image = getAbsolutePath("wbt/uploads/$path/$newfilename");

    return [
        "status" => $move,
        "imagePath" => $image,
        "imageName" => $newfilename
    ];
}