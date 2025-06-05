<?php
$conn = new mysqli("localhost", "root", "", "learning");
$id = intval($_GET['id']);
$action = $_GET['action'] === 'read' ? 1 : 0;
$conn->query("UPDATE contact_messages SET is_read=$action WHERE id=$id");
$conn->close();
?>
