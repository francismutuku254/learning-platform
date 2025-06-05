<?php
header('Content-Type: application/json');
$host = 'localhost'; // your DB host
$db   = 'learning';
$user = 'root';
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  echo json_encode(['success' => false, 'message' => 'DB connection failed']);
  exit;
}

// Read JSON POST data
$data = json_decode(file_get_contents('php://input'), true);
if(!$data || !isset($data['type'], $data['id'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid input']);
  exit;
}

$type = $conn->real_escape_string($data['type']);
$id = intval($data['id']);

switch($type) {
  case 'quiz':
    // Delete quiz questions first (foreign key)
    $conn->query("DELETE FROM quiz_questions WHERE quiz_id = $id");
    $del = $conn->query("DELETE FROM quizzes WHERE id = $id");
    break;
  case 'note':
    // Get file path to delete file from server
    $fileRes = $conn->query("SELECT file_path FROM notes WHERE id = $id");
    if($fileRes && $fileRes->num_rows > 0) {
      $fileRow = $fileRes->fetch_assoc();
      $file_path = $fileRow['file_path'];
      if(file_exists($file_path)) {
        unlink($file_path); // delete file
      }
    }
    $del = $conn->query("DELETE FROM notes WHERE id = $id");
    break;
  case 'activity':
    $del = $conn->query("DELETE FROM activities WHERE id = $id");
    break;
  default:
    echo json_encode(['success' => false, 'message' => 'Invalid type']);
    exit;
}

if($del) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Delete query failed']);
}

$conn->close();
?>
