<?php
$to = $_POST['email'];
$message = $_POST['reply_message'];
$subject = "Reply from CBC Tech Learn";

$headers = "From: cbctechlearn@example.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8";

if (mail($to, $subject, $message, $headers)) {
  echo "Reply sent successfully!";
} else {
  echo "Failed to send reply.";
}
?>
