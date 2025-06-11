<?php
// Enable full error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database config
$host = 'localhost';
$dbname = 'csit_events';
$username = 'root';  // adjust if needed
$password = '';      // adjust if needed

// Create mysqli connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . htmlspecialchars($conn->connect_error);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Check for empty fields
    if (!$name || !$email || !$subject || !$message) {
        http_response_code(400);
        echo "All fields are required.";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email address.";
        exit;
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo "Database error: " . htmlspecialchars($conn->error);
        exit;
    }

    // Bind parameters and execute
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "Thank you, $name! Your message has been received.";
    } else {
        http_response_code(500);
        echo "Failed to save your message: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Invalid request method.";
}

$conn->close();
