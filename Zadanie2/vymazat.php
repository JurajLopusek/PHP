<?php
$dbSeverName = "localhost";
$dbUsername = "";
$dbPassword = "";
$dbName = "";
try {
    $conn = new PDO("mysql:host=$dbSeverName;dbname=$dbName", $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("DELETE FROM rozvrh");
    $stmt->execute();
    echo "Všetky záznamy boli vymazané.";
} catch(PDOException $e) {
    echo "Vymazanie záznamov z tabuľky zlyhalo: " . $e->getMessage();
}
$conn = null;
?>
