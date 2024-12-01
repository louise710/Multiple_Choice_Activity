<?php 
$serverName = "localhost";
$username = "root";
$password = "root";
$dbname = "appsdev_finals";
$port = 3306; 

$data = new mysqli($serverName, $username, $password, $dbname, $port);

if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

?>