<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $message = $_POST['message'];

  $stmt = $conn->prepare("INSERT INTO notifications (message) VALUES (?)");
  $stmt->bind_param("s", $message);
  $stmt->execute();
  $stmt->close();

  echo json_encode(["status" => "success"]);
} else {
  echo json_encode(["status" => "error"]);
}
?>
