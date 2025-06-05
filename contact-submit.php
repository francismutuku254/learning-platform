<?php
// DB connection
$host = 'localhost';
$user = 'root';
$password = "";
$dbname = 'learning';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and insert data
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$subject = $conn->real_escape_string($_POST['subject']);
$message = $conn->real_escape_string($_POST['message']);

$sql = "INSERT INTO contact_messages (name, email, subject, message) 
        VALUES ('$name', '$email', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Thank you! Your message has been received.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
