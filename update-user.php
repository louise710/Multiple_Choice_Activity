<?php
include 'database.php';

// Fetch user details if it's a GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $sql = "SELECT u.userName AS username, r.totalScore AS score 
            FROM username u 
            LEFT JOIN results r ON u.userId = ?";

    $stmt = $data->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $data->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user_data = $result->fetch_assoc();
    } else {
        die("User not found.");
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission for updating the user
    header('Content-Type: application/json');

    $user_id = intval($_POST['user_id']);
    $username = trim($_POST['username']);
    $score = intval($_POST['score']);

    if (!$user_id || !$username || !is_numeric($score)) {
        echo json_encode(["error" => "Invalid input"]);
        exit();
    }

    // Update username
    $sql_username = "UPDATE username SET userName = ? WHERE userId = ?";
    $stmt = $data->prepare($sql_username);
    if (!$stmt) {
        echo json_encode(["error" => "Error preparing statement: " . $data->error]);
        exit();
    }
    $stmt->bind_param("si", $username, $user_id);

    if (!$stmt->execute()) {
        echo json_encode(["error" => "Error updating username: " . $stmt->error]);
        exit();
    }
    $stmt->close();

    // Update score
    $sql_score = "UPDATE results SET totalScore = ? WHERE userId = ?";
    $stmt = $data->prepare($sql_score);
    if (!$stmt) {
        echo json_encode(["error" => "Error preparing statement: " . $data->error]);
        exit();
    }
    $stmt->bind_param("ii", $score, $user_id);

    if ($stmt->execute()) {
        header("Location: ../admin.php");
        exit();
    } else {
        echo json_encode(["error" => "Error updating score: " . $stmt->error]);
    }
   

    $stmt->close();
    $data->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 40%;
            margin: auto;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <!-- <h2>Update User</h2> -->
    <form method="post" action="logic/update-user.php">
        <input type="hidden" name="user_id" id="user-id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <table class="table table-bordered mt-3">
            <tr>
                <td>Nickname:</td>
                <td>
                    <input type="text" name="username" autocomplete="off" class="form-control" 
                           value="<?php echo isset($user_data['username']) ? htmlspecialchars($user_data['username']) : ''; ?>">
                </td>
            </tr>
            <tr>
                <td>Score:</td>
                <td>
                    <input type="number" name="score" autocomplete="off" class="form-control" 
                           value="<?php echo isset($user_data['score']) ? htmlspecialchars($user_data['score']) : ''; ?>">
                </td>
            </tr>
        </table>
        <div class="text-center mt-3">
            <input type="submit" name="update" class="btn btn-primary" value="Update">
        </div>
    </form>
</body>
</html>
