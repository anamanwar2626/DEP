<?php
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM messages ORDER BY created_at ASC");
$messages = array();

while ($row = $result->fetch_assoc()) {
  $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
