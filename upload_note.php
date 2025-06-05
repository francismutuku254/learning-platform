<?php
$mysqli = new mysqli("localhost", "root", "", "learning");

if ($mysqli->connect_errno) {
  die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

$note_title = $_POST['note_title'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["note_file"]["name"]);
$file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Allowed file extensions
$allowed_extensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt'];

if (in_array($file_extension, $allowed_extensions)) {
  if (move_uploaded_file($_FILES["note_file"]["tmp_name"], $target_file)) {
    $stmt = $mysqli->prepare("INSERT INTO notes (title, file_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $note_title, $target_file);
    $stmt->execute();
    $stmt->close();
    echo "Notes uploaded successfully.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
} else {
  echo "Invalid file format. Only PDF, DOC, DOCX, PPT, PPTX, and TXT are allowed.";
}
?>
