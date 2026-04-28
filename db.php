<?php
$host = "localhost";
$user = "ucota1";
$pass = "ucota1";
$db   = "ucota1";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
