<?php
session_start();

// Only allow access if logged in and role is teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

/// Database connection
$host = 'localhost';
$db   = 'learning';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Fetch all users
$sql = "SELECT id, fullname, phone, residence, role, status FROM users ORDER BY id DESC";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($users);
?>
