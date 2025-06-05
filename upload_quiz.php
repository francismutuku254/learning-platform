<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "learning");

// Check connection
if ($mysqli->connect_errno) {
  die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Retrieve form data
$quiz_title = $_POST['quiz_title'];
$questions = $_POST['questions'];
$option_a = $_POST['option_a'];
$option_b = $_POST['option_b'];
$option_c = $_POST['option_c'];
$option_d = $_POST['option_d'];
$correct_option = $_POST['correct_option'];

// Insert quiz title
$stmt = $mysqli->prepare("INSERT INTO quizzes (title) VALUES (?)");
$stmt->bind_param("s", $quiz_title);
$stmt->execute();
$quiz_id = $stmt->insert_id;
$stmt->close();

// Insert questions
$stmt = $mysqli->prepare("INSERT INTO quiz_questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
for ($i = 0; $i < count($questions); $i++) {
  $stmt->bind_param("issssss", $quiz_id, $questions[$i], $option_a[$i], $option_b[$i], $option_c[$i], $option_d[$i], $correct_option[$i]);
  $stmt->execute();
}
$stmt->close();

echo "Quiz uploaded successfully.";
?>
