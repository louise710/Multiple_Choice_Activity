<?php

include("database.php");

$questionQuery = "SELECT * FROM questions";
$questions = $data->query($questionQuery);

$questionData = [];

$nameQuery = "SELECT * FROM username ORDER BY userId DESC LIMIT 1";
$username = $data->query($nameQuery);


if($questions){
    while($row = $questions->fetch_assoc()) {
        $questionData[] = $row;
    }
}

if($username){
    if($row = $username->fetch_assoc()){
        $usernameData = $row;
    }
}


$pageData = [
    'questions' => $questionData,
    'username' => $usernameData
];


echo json_encode($pageData);

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $userID = $_POST['nicknameID'];
    $total_time = $_POST['total-time'];
    $total_score = $_POST['total-score'];

    $pushData = $data-> prepare("INSERT INTO results (totalScore,totalTime,userId) VALUES (?,?,?)");
    $pushData->bind_param("iii", $total_score, $total_time, $userID);
    $pushData->execute();

    header("Location:../views/third-page.html");
}

?>