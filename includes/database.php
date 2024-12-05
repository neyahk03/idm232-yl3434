<?php
// $db_server = getenv('DB_SERVER');
// $db_username = getenv('DB_USERNAME');
// $db_password = getenv('DB_PASSWORD');
// $db_name = getenv('DB_NAME');

$db_server = 'localhost';
$db_username = 'root';
$db_password = 'root';
$db_name = 'recipes_test_run';

// $db_server = getenv('DB_SERVER');
// $db_username = getenv('DB_USERNAME');
// $db_password = getenv('DB_PASSWORD');
// $db_name = getenv('DB_NAME');


// Establish a connection to the database
$connection = new mysqli($db_server, $db_username, $db_password, $db_name);

// Check for connection errors
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// echo "Database connected successfully!";
?>