<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "learning");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode([]);
    exit();
}

$res = $mysqli->query("SELECT title, event_date FROM activities WHERE event_date >= CURDATE() ORDER BY event_date ASC");
$activities = [];
while ($row = $res->fetch_assoc()) {
    $activities[] = $row;
}

echo json_encode($activities);
?>
