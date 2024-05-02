<?php
$dbSeverName = "localhost";
$dbUsername = "";
$dbPassword = "";
$dbName = "";

$conn = mysqli_connect($dbSeverName, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}