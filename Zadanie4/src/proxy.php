<?php
$city = $_GET['city'];

$url = "https://api.weatherapi.com/v1/current.json?key=a3dea48157e3485faab172744242204&q=" . urlencode($city) . "&aqi=no";
$response = file_get_contents($url);

header('Content-Type: application/json');
echo $response;
?>
