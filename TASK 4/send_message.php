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

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$message = $data['message'];

$stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $message);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'success']);
?>
