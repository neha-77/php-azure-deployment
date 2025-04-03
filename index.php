<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "formdb.mysql.database.azure.com";
$username = "dbadmin"; // Your MySQL user
$password = "Secure@1234";
$database = "event_registration";
$port = 3306;

// SSL Certificate Path (Use Correct Azure Path)
$ssl_ca = "/home/site/wwwroot/certs/DigiCertGlobalRootCA.crt.pem";
mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, NULL, NULL);


// Create MySQLi connection with SSL
$conn = mysqli_init();
if (!$conn) {
    die("MySQL initialization failed.");
}

// Enable SSL for MySQL connection
mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, NULL, NULL);
$success = mysqli_real_connect($conn, $servername, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);

if (!$success) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Debugging: Check if data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($_POST);
}

// Validate and sanitize input fields
$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : null;
$email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : null;
$phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : null;
$event = isset($_POST['event']) ? htmlspecialchars(trim($_POST['event'])) : null;
$date = isset($_POST['date']) ? htmlspecialchars(trim($_POST['date'])) : null;
$category = isset($_POST['category']) ? htmlspecialchars(trim($_POST['category'])) : null;
$comments = isset($_POST['comments']) ? htmlspecialchars(trim($_POST['comments'])) : null;

// Check if required fields are missing
if (!$name || !$email || !$phone || !$event || !$date || !$category) {
    die("Error: Required fields missing.");
}

// Prepare SQL statement
$sql = "INSERT INTO registrations (name, email, phone, event_name, event_date, category, comments) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

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
