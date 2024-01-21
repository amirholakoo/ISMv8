<?php
// connet_db.php: Database Connection Script
$servername = "localhost";
$username = "admin";
$password = "pi";
$dbname = "ISMv8";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
