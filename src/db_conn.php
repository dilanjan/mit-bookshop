<?php
$servername = 'localhost';
$username = 'awo';
$password = 'password';
$dbname = 'bookshop';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

