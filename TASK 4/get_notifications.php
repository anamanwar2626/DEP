<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC");
$notifications = [];
while ($row = $result->fetch_assoc()) {
  $notifications[] = $row;
}

echo json_encode($notifications);
?>
