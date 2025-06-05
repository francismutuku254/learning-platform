<?php
header('Content-Type: application/json');
// Database connection
$host = 'localhost';
$db   = 'learning';
$user = 'root';
$pass = '';


$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  echo json_encode(['success' => false, 'message' => 'DB connection failed']);
  exit;
}

$result = ['success' => true];

// Fetch quizzes
$q_res = $conn->query("SELECT id, title FROM quizzes ORDER BY id DESC");
$quizzes = [];
if($q_res) {
  while($row = $q_res->fetch_assoc()) {
    $quizzes[] = $row;
  }
}
$result['quizzes'] = $quizzes;

// Fetch notes
$n_res = $conn->query("SELECT id, title, file_path, uploaded_at FROM notes ORDER BY uploaded_at DESC");
$notes = [];
if($n_res) {
  while($row = $n_res->fetch_assoc()) {
    $notes[] = $row;
  }
}
$result['notes'] = $notes;

// Fetch activities
$a_res = $conn->query("SELECT id, title, event_date, created_at FROM activities ORDER BY created_at DESC");
$activities = [];
if($a_res) {
  while($row = $a_res->fetch_assoc()) {
    $activities[] = $row;
  }
}
$result['activities'] = $activities;

echo json_encode($result);
$conn->close();
?>
