<?php
$servername = '192.168.70.4';
$username = 'lara';
$password = 'lara';
$dbname = 'lara';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

