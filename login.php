<?php
session_start();

// Database connection
$host = 'localhost';
$db   = 'learning';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, fullname, password_hash, role, status FROM users WHERE fullname = ? OR phone = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            if ($user['status'] !== 'approved') {
                $message = "Your account is pending approval. Please wait for a teacher to approve your registration.";
            } else {
                // Login successful, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'teacher') {
                    header("Location: teacher_dash.php");
                } else {
                    header("Location: explore.php");
                }
                exit();
            }
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User not found.";
    }

    $stmt->close();
}
$conn->close();

// Show message using JavaScript alert
if (!empty($message)) {
    echo "<script>alert('$message'); window.location.href='login_page.html';</script>";
    exit();
}
?>
