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
    <tr>
        <?php
        ?>
        <td>1</td>
        <td>NJ</td>
        <td>12</td>
        <td>
            <button onclick="openModal(1, 'NJ', 12)">Edit</button>
            <button onclick="confirmDelete(1)">Delete</button>
        </td>
    </tr>
    <tr>
        <td>2</td>
        <td>Miss/Maam</td>
        <td>20</td>
        <td>
            <button onclick="openModal(2, 'Miss/Maam', 20)">Edit</button>
            <button onclick="confirmDelete(2)">Delete</button>
        </td>
    </tr>
    <tr>
        <td>3</td>
        <td>Sample</td>
        <td>7</td>
        <td>
            <button onclick="openModal(3, 'Sample', 7)">Edit</button>
            <button onclick="confirmDelete(3)">Delete</button>
        </td>
    </tr>
    <tr>
        <td>4</td>
        <td>Lowes</td>
        <td>0</td>
        <td>
            <button onclick="openModal(4, 'Lowes', 0)">Edit</button>
            <button onclick="confirmDelete(4)">Delete</button>
        </td>
    </tr>
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
