<?php
$db_host = 'localhost';
$db_name= 'php_login';
$db_user = 'root';
$db_pass = ''; // Corrected variable name

header('Content-Type: application/json');
try{
    $db = new PDO("mysql:host=${db_host};dbname=${db_name}", $db_user, $db_pass);   
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}
catch(PDOException $e){ 
    echo $e->getMessage();
}
?>