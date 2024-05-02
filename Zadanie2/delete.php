<?php
$dbSeverName = "localhost";
$dbUsername = "xlopusek";
$dbPassword = "Juraj2001";
$dbName = "rozvrh";

$conn = new mysqli($dbSeverName, $dbUsername, $dbPassword, $dbName);

$id = $_GET['id'];
echo $id;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    if ($id) {
        $url = "https://node71.webte.fei.stuba.sk/Zadanie2/api.php?id=$id";
        $options = [
            'http' => [
                'method' => 'DELETE',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result !== false) {
            header('location:index.php');
        } else {
            echo "Chyba při odstraňování záznamu s ID $id.";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Record</title>
</head>

<body>
    <h2>Odstranit záznam</h2>
    <form action="delete.php" method="POST">
        <p>Opravdu chcete odstranit tento záznam?</p>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="Ano">
    </form>
</body>

</html>