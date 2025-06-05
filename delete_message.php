<?php
$conn = new mysqli("localhost", "root", "", "learning");
$id = intval($_GET['id']);
$conn->query("DELETE FROM contact_messages WHERE id=$id");
$conn->close();
?>
