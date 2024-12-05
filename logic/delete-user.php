<?php
header('Content-Type: application/json');

include 'database.php';

$user_id = $_GET['id'];

$sql_results = "DELETE FROM results WHERE userid = ?";
$stmt = $data->prepare($sql_results);
$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Error deleting user from results: " . $stmt->error]);
    $stmt->close();
    $data->close();
    exit();
}

$stmt->close();

$sql_user = "DELETE FROM username WHERE userid = ?";
$stmt = $data->prepare($sql_user);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "User deleted successfully"]);
} else {
    echo json_encode(["error" => "Error deleting user: " . $stmt->error]);
}

$stmt->close();
$data->close();
?>
