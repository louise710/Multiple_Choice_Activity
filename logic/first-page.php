<?php

include("database.php");

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $userInput = $_POST['nickname'];

    $pushData = $data->prepare("INSERT INTO username (userName) VALUES (?)");  
    $pushData->bind_param("s", $userInput);
    $pushData->execute();

    header("Location:../views/second-page.html");
}
?>