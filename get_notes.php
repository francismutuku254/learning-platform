<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "learning");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode([]);
    exit();
}

$res = $mysqli->query("SELECT title, file_path FROM notes ORDER BY uploaded_at DESC");
$notes = [];
while ($row = $res->fetch_assoc()) {
    $notes[] = $row;
}

echo json_encode($notes);
?>
