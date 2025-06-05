<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "learning");
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed."]);
  exit();
}

$quiz_id = $_POST['quiz_id'] ?? null;
$quiz_title = $_POST['quiz_title'] ?? null;
$score = $_POST['score'] ?? null;
$total = $_POST['total'] ?? null;
$percentage = $_POST['percentage'] ?? null;

// Basic validation
if (!is_numeric($quiz_id) || !is_numeric($score) || !is_numeric($total) || !is_numeric($percentage) || empty($quiz_title)) {
  http_response_code(400);
  echo json_encode(["error" => "Invalid input."]);
  exit();
}

$stmt = $conn->prepare("INSERT INTO quiz_results (quiz_id, quiz_title, score, total, percentage) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isiii", $quiz_id, $quiz_title, $score, $total, $percentage);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  http_response_code(500);
  echo json_encode(["error" => "Failed to save result."]);
}

$stmt->close();
$conn->close();
?>
