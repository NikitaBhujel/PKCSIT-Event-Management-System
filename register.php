<?php
$servername = "localhost";
$username = "root";
$password = "";  // Change this if you set a MySQL root password
$dbname = "event";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate inputs
$ID = htmlspecialchars(trim($_POST['ID']));
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$phone = htmlspecialchars(trim($_POST['phone']));
$address = htmlspecialchars(trim($_POST['address']));
$semester = htmlspecialchars(trim($_POST['semester']));
$team = htmlspecialchars(trim($_POST['team']));
$message = htmlspecialchars(trim($_POST['message']));

$sql = "INSERT INTO registration (ID,name, email, phone, address, semester, team, message)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
  $stmt->bind_param("sssssss",$ID, $name, $email, $phone, $address, $semester, $team, $message);
  if ($stmt->execute()) {
    header("Location: success.php");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }
  $stmt->close();
} else {
  echo "Error preparing statement: " . $conn->error;
}

$conn->close();
?>
