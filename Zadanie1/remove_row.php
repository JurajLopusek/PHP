<?php
session_start();
require 'includes/include2.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $pdo->beginTransaction();

    $sql_delete_receivers = "DELETE FROM prizes WHERE person_id = :id";
    $prize_details_id = "SELECT prize_details_id FROM prizes WHERE person_id = :id";
    $stmt_prize_detail_id = $pdo->prepare($prize_details_id);
    $stmt_prize_detail_id->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_prize_detail_id->execute();
    $prize_detail_id = $stmt_prize_detail_id->fetchColumn();
    $stmt_delete_receivers = $pdo->prepare($sql_delete_receivers);
    $stmt_delete_receivers->bindParam(':id', $id, PDO::PARAM_INT);
    $uspech_mazania_prijemcov = $stmt_delete_receivers->execute();

    $sql_delete_prizes = "DELETE FROM receivers WHERE id = :id";
    $stmt_delete_prizes = $pdo->prepare($sql_delete_prizes);
    $stmt_delete_prizes->bindParam(':id', $id, PDO::PARAM_INT);
    $uspech_mazania_cien = $stmt_delete_prizes->execute();

    $sql_delete_prizes_detail = "DELETE FROM prize_details WHERE id = :prize_detail_id";
    $stmt_delete_prizes_detail = $pdo->prepare($sql_delete_prizes_detail);
    $stmt_delete_prizes_detail->bindParam(':prize_detail_id', $prize_detail_id, PDO::PARAM_INT);
    $uspech_mazania_cien_detail = $stmt_delete_prizes_detail->execute();

    $successRemove = 0;
    if ($uspech_mazania_prijemcov && $uspech_mazania_cien && $uspech_mazania_cien_detail) {
        $successRemove = 1;
        $pdo->commit();
        header('location: index.php?successRemove=' . $successRemove . '');
        exit;
    } else {
        $pdo->rollBack();
        echo "Chyba při mazání záznamů";
    }
} else {
    echo "Neplatný požadavek";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove</title>
</head>

<body>
    <h1>Záznam bol úspešne odstránený</h1>
    <p>Záznam bol úspešne odstránený z databázy.</p>
    <a href="index.php">Späť na hlavnú stránku</a>
</body>
<?php
require 'includes/include2.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
</html>