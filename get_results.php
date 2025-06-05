<?php
header('Content-Type: application/json');

// DB connection (adjust credentials)
$host = 'localhost';
$db   = 'learning';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$sql = "SELECT quiz_id,quiz_title, score, total, percentage, result_date 
        FROM quiz_results
        ORDER BY result_date DESC
        LIMIT 50";  // limit recent 50 results

$result = $conn->query($sql);

$results = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}

echo json_encode($results);

$conn->close();
