<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php

// Database credentials
$servername = "formdb.mysql.database.azure.com"; // Your Azure MySQL server
$username = "dbadmin"; // Use "username@servername"
$password = "Secure@1234"; // Your MySQL password
$database = "event_registration"; // Your database name
$port = 3306;

// SSL options
$ssl_ca = "/etc/ssl/certs/DigiCertGlobalRootCA.crt.pem"; // Path to Azure MySQL SSL certificate

// Create MySQLi connection with SSL
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL);

// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$event = $_POST['event'];
$date = $_POST['date'];
$category = $_POST['category'];
$comments = $_POST['comments'];

// Prepare SQL statement
$sql = "INSERT INTO registrations (name, email, phone, event_name, event_date, category, comments)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $email, $phone, $event, $date, $category, $comments);

// Execute and check if successful
if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
