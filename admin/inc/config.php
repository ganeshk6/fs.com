<?php
// Error Reporting Turn On
ini_set('error_reporting', E_ALL);

// Setting up the time zone
date_default_timezone_set('America/Los_Angeles');

// Host Name
$dbhost = 'localhost';

// Database Name
$dbname = 'clenfay';

// Database Username
$dbuser = 'root';

// Database Password
$dbpass = '';

// Defining base url
define("BASE_URL", "http://localhost/fscom/");

// Getting Admin url
define("ADMIN_URL", BASE_URL . "admin/");

try {
    // Notice the addition of port=3307 in the DSN
    $pdo = new PDO("mysql:host={$dbhost};port=3307;dbname={$dbname}", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
    exit(); // Ensure the script stops execution if the connection fails
}
?>
