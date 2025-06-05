<?php
session_start();

// Only allow access if logged in and role is teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login_page.html");
    exit();
}

// Database connection
$host = 'localhost';
$db   = 'learning';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;
    if ($userId) {
        $userId = intval($userId);

        if (isset($_POST['approve'])) {
            $conn->query("UPDATE users SET status = 'approved' WHERE id = $userId");
        } elseif (isset($_POST['promote'])) {
            $conn->query("UPDATE users SET role = 'teacher' WHERE id = $userId");
        } elseif (isset($_POST['delete'])) {
            $conn->query("DELETE FROM users WHERE id = $userId");
        }
    }
}

// Fetch all users
$users = $conn->query("SELECT id, fullname, phone, residence, role, status FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | CBC Tech Learn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
            background-color: #f7f7f7;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 1rem;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #0066cc;
            color: #fff;
        }

        form {
            display: inline;
        }

        button {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 4px;
        }

        .approve { background-color: #28a745; color: white; }
        .promote { background-color: #ffc107; color: black; }
        .delete { background-color: #dc3545; color: white; }

        .logout-link {
            display: block;
            text-align: right;
            margin-bottom: 1rem;
        }

        .logout-link a {
            color: #0066cc;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            td {
                position: relative;
                padding-left: 50%;
            }

            td::before {
                position: absolute;
                top: 0;
                left: 0;
                width: 45%;
                padding-left: 1rem;
                white-space: nowrap;
                font-weight: bold;
            }

            td:nth-of-type(1)::before { content: "Full Name"; }
            td:nth-of-type(2)::before { content: "Phone"; }
            td:nth-of-type(3)::before { content: "Residence"; }
            td:nth-of-type(4)::before { content: "Role"; }
            td:nth-of-type(5)::before { content: "Status"; }
            td:nth-of-type(6)::before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <div class="logout-link">
        <a href="teacher_dash.php">Back to Dashboard</a>
    </div>

    <h2>Admin Panel: Manage Users</h2>

    <div class="table-container">
        <table id="users-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Residence</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- User rows will be inserted here by JavaScript -->
            </tbody>
        </table>
    </div>

        <script>
        function fetchUsers() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_users.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const users = JSON.parse(xhr.responseText);
                    const tbody = document.querySelector('#users-table tbody');
                    tbody.innerHTML = '';

                    users.forEach(user => {
                        const tr = document.createElement('tr');

                        tr.innerHTML = `
                            <td>${user.fullname}</td>
                            <td>${user.phone}</td>
                            <td>${user.residence}</td>
                            <td>${user.role}</td>
                            <td>${user.status}</td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="user_id" value="${user.id}">
                                    ${user.status === 'pending' ? '<button type="submit" name="approve" class="approve">Approve</button>' : ''}
                                    ${user.role === 'student' ? '<button type="submit" name="promote" class="promote">Promote</button>' : ''}
                                    <button type="submit" name="delete" class="delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </td>
                        `;

                        tbody.appendChild(tr);
                    });
                } else {
                    console.error('Failed to load users:', xhr.statusText);
                }
            };
            xhr.send();
        }

        // Initial fetch and auto-refresh every 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            fetchUsers();
            setInterval(fetchUsers, 5000); // Auto-refresh every 5s
        });
    </script>

</body>
</html>

<?php $conn->close(); ?>