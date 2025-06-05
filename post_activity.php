<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "learning");

// Check connection
if ($mysqli->connect_errno) {
  die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Retrieve form data
$activity_title = $_POST['activity_title'];
$event_date = $_POST['event_date'];

// Insert into database
$stmt = $mysqli->prepare("INSERT INTO activities (title, event_date) VALUES (?, ?)");
$stmt->bind_param("ss", $activity_title, $event_date);
$stmt->execute();
$stmt->close();

echo "Activity posted successfully.";
?>
