<?php
header('Content-Type: application/json');

include 'database.php';

$user_id = $_POST['user_id'];
$username = $_POST['username'];
$score = $_POST['score'];

$sql_username = "UPDATE username SET userName = ? WHERE userid = ?";
$stmt = $data->prepare($sql_username);
$stmt->bind_param("si", $username, $user_id);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Error updating username: " . $stmt->error]);
    $stmt->close();
    $data->close();
    exit();
}

$stmt->close();

$sql_score = "UPDATE results SET totalScore = ? WHERE userid = ?";
$stmt = $data->prepare($sql_score);
$stmt->bind_param("ii", $score, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "User updated successfully"]);
} else {
    echo json_encode(["error" => "Error updating score: " . $stmt->error]);
}

$stmt->close();
$data->close();

?>
