<?php
// Database connection settings
$host = "localhost";  // usually localhost
$username = "root";   // your db username
$password = "";       // your db password
$database = "csit_events"; // your db name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $semester = trim($_POST['semester'] ?? '');
    $teamname = trim($_POST['teamname'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation for required fields
    if (empty($fullname) || empty($email) || empty($phone) || empty($address) || empty($semester) || empty($teamname)) {
        die("Please fill all the required fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid email address.");
    }

    // Prepare and bind to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO registrations (fullname, email, phone, address, semester, teamname, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $fullname, $email, $phone, $address, $semester, $teamname, $message);

    if ($stmt->execute()) {
        echo "Registration successful! Thank you for registering.";
        // You can redirect or do something else here, e.g.
        // header("Location: thankyou.html");
        // exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
