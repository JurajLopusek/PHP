<?php
$host = "localhost";
$user = "xlopusek";
$pass = "Juraj2001";
$db = "nobel3_prizes";
try {
    $pdo = new PDO ("mysql:host=$host; dbname=$db; charset=utf8", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    } catch(PDOException $e){
        die("Chyba připojení k databázi: " . $e->getMessage());
    }
?>