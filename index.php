<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "formdb.mysql.database.azure.com";
$username = "dbadmin";
$password = "Secure@1234";
$database = "event_registration";
$port = 3306;

// SSL Certificate Path
$ssl_ca = "/home/site/wwwroot/certs/DigiCertGlobalRootCA.crt.pem";

// Initialize MySQLi connection
$conn = mysqli_init();
if (!$conn) {
    die("MySQL initialization failed.");
}

// Set SSL
mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, NULL, NULL);

// Attempt connection
if (!mysqli_real_connect($conn, $servername, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Debugging: Check if data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($_POST);
}

// Validate and sanitize input fields
$name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : null;
$email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : null;
$phone = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : null;
$event = isset($_POST['event']) ? trim(htmlspecialchars($_POST['event'])) : null;
$date = isset($_POST['date']) ? trim(htmlspecialchars($_POST['date'])) : null;
$category = isset($_POST['category']) ? trim(htmlspecialchars($_POST['category'])) : null;
$comments = isset($_POST['comments']) ? trim(htmlspecialchars($_POST['comments'])) : null;

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

// Bind parameters and execute
$stmt->bind_param("sssssss", $name, $email, $phone, $event, $date, $category, $comments);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
