<?php  

define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', '');
define('DB', 'school');


$DSN = "mysql:host=" . HOST . ";dbname=" . DB;
$conn = new PDO($DSN, USER, PASSWORD);

// ATTRIBUTES  
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);