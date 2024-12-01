<?php

include("database.php");

$nameQuery = "SELECT * FROM username ORDER BY userId DESC LIMIT 1";
$username = $data->query($nameQuery)->fetch_assoc();

$rankQuery = "SELECT u.username, r.totalScore, r.totalTime FROM results r JOIN username u ON r.userId = u.userId ORDER BY r.totalScore DESC LIMIT 10";
$getrankings = $data->query($rankQuery);

$resultsQuery = "SELECT * FROM results ORDER BY userId DESC LIMIT 1";
$resultsData = $data->query($resultsQuery )->fetch_assoc();

$rankings = [];

if($getrankings){
    while($row = $getrankings->fetch_assoc()) {
        $rankings[] = $row;
    }
}
$pageData = [
    'username' => $username,
    'rankings' => $rankings,
    'results' => $resultsData
];

echo json_encode($pageData);
?>
