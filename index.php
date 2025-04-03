<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "formdb.mysql.database.azure.com"; // Your Azure MySQL server
$username = "dbadmin"; // Use "username@servername"
$password = "Secure@1234"; // Your MySQL password
$database = "event_registration"; // Your database name
$port = 3306;

// SSL options
$ssl_ca = "/etc/ssl/certs/DigiCertGlobalRootCA.crt.pem";


// Create MySQLi connection with SSL
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL);

// Check connection
if (mysqli_connect_errno()) {
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
