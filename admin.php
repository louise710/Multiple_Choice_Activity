<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Modal Styles */
        #update-modal .modal-content {
            padding: 20px;
            background: #f9f9f9;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            float: right;
            cursor: pointer;
        }

        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Admin Panel</h1>
        <div class="table-responsive">
            <table id="users-table" class="table table-striped table-bordered">
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
                                        <button class='btn btn-primary btn-sm' onclick=\"openModal(" . htmlspecialchars($row['id']) . ")\">Update</button>
                                        <button class='btn btn-danger btn-sm' onclick=\"confirmDelete('{$row['id']}')\">Delete</button>
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
        </div>
    </div>

    <!-- Update Modal -->
    <div id="update-modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User</h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <div id="update-user"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#users-table').DataTable();
        });

        function openModal(id) {
            document.getElementById("update-user").innerHTML = "";
            document.getElementById("update-modal").style.display = "none";

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        document.getElementById("update-user").innerHTML = this.responseText;
                        $('#update-modal').modal('show');

                        const form = document.getElementById('update-form');
                        form.addEventListener('submit', function (event) {
                            event.preventDefault();
                            const formData = new FormData(form);

                            fetch('logic/update-user.php', {
                                method: 'POST',
                                body: formData
                            })
                                .then(response => response.json())
                                .then(data => {
                                    alert(data.message || data.error);
                                    closeModal();
                                    location.reload(); // Refresh table
                                })
                                .catch(err => console.error('Error:', err));
                        });
                    } else {
                        console.error('Failed to load modal content:', this.status);
                        alert('Failed to load user details. Please try again.');
                    }
                }
            };
            xhttp.open("GET", "logic/update-user.php?id=" + id, true);
            xhttp.send();
        }

        function closeModal() {
            $('#update-modal').modal('hide');
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
                })
                .catch(err => console.error('Error:', err));
            }
        }
    </script>
</body>
</html>
