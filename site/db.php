<?php
// Database connection parameters
$servername = "mysql_db"; // Docker service name for the MySQL container
$username = "root";
$password = "root_password";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>