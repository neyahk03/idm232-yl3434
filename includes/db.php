<?php

$db_server = 'localhost';
$db_username = 'yl3434';
$db_password = 'V/j9wEa9r67EBhxo';
$db_name = 'yl3434_db';



$connection = new mysqli($db_server, $db_username, $db_password, $db_name);


if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


?>