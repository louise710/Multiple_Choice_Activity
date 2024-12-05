<?php
header('Content-Type: application/json');

include 'database.php';

$sql = "SELECT u.userid AS id, u.userName AS username, r.totalScore AS score 
        FROM username u
        LEFT JOIN results r ON u.userid = r.userid";

$result = $data->query($sql);

if ($result->num_rows > 0) {
    $users = [];
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode([]);
}

$data->close();
?>
