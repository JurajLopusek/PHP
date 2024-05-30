<?php
$servername = "mysql";
$username = "xkubalec";
$password = "rado-bezpecne-heslo1";
$dbname = "webteFinal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>