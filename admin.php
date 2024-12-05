<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../asset/css/styles.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <table id="users-table" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'logic/database.php';

            $sql = "SELECT u.userId AS id, u.userName AS username, r.totalScore AS score 
                    FROM username u 
                    LEFT JOIN results r ON u.userId = r.userId";

            $stmt = $data->prepare($sql);
            if ($stmt === false) {
                die("Error preparing statement: " . $data->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['score']}</td>
                            <td>
                                <button onclick=\"openModal('{$row['id']}', '{$row['username']}', '{$row['score']}')\">Edit</button>
                                <button onclick=\"confirmDelete('{$row['id']}')\">Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found.</td></tr>";
            }

            $stmt->close();
            $data->close();
            ?>
        </tbody>
    </table>

    <div id="update-modal" style="display: none;">
        <form id="update-form">
            <h2>Update User</h2>
            <input type="hidden" name="user_id" id="user-id">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="score">Score:</label>
            <input type="number" name="score" id="score" required>
            <button type="submit">Update</button>
            <button type="button" id="cancel-update">Cancel</button>
        </form>
    </div>

    <script>
        function openModal(id, username, score) {
            document.querySelector('#user-id').value = id;
            document.querySelector('#username').value = username;
            document.querySelector('#score').value = score;
            document.querySelector('#update-modal').style.display = 'block';
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`logic/delete-user.php?id=${id}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
            }
        }

        document.querySelector('#cancel-update').addEventListener('click', () => {
            document.querySelector('#update-modal').style.display = 'none';
        });

        document.querySelector('#update-form').addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(event.target);

            fetch('logic/update-user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            });
        });
    </script>
</body>
</html>
