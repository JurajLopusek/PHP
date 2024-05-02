<?php
$dbSeverName = "localhost";
$dbUsername = "";
$dbPassword = "";
$dbName = "";

$conn = new mysqli($dbSeverName, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $den = $_POST['den'] ?? null;
    $od = $_POST['od'] ?? null;
    $do = $_POST['do'] ?? null;
    $predmet = $_POST['predmet'] ?? null;
    $akcia = $_POST['akcia'] ?? null;
    $miestnost = $_POST['miestnost'] ?? null;
    $vyucujuci = $_POST['vyucujuci'] ?? null;
    $obmedzenie = $_POST['obmedzenie'] ?? null;
    $kapacita = $_POST['kapacita'] ?? null;

    if ($den !== null && $od !== null && $do !== null && $predmet !== null && $akcia !== null && $miestnost !== null && $vyucujuci !== null && $kapacita !== null) {
        $new_data = [
            'den' => $den,
            'od' => $od,
            'do' => $do,
            'predmet' => $predmet,
            'akcia' => $akcia,
            'miestnost' => $miestnost,
            'vyucujuci' => $vyucujuci,
            'obmedzenie' => $obmedzenie,
            'kapacita' => $kapacita
        ];
            $url = "https://node71.webte.fei.stuba.sk/Zadanie2/api.php";
            $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($new_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                ]
            ];
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result !== false) {
                header('Location: index.php');
                exit;
            }
        } else {
            echo "Niektoré z požadovaných dát chýbajú.";
        }
}
?>
<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <link rel="stylesheet" type="text/css" href="pridat.css">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Pridat</title>
</head>

<body>
    <nav>
        <ul class="moznosti">
            <li><a href="pridat.php" title="Pridať">Pridať</a></li>
            <li><a href="z_prace.php" title="Práce">Práce</a></li>
            <li><a href="index.php" title="Domov">Domov</a></li>
            <li><a href="swagger-ui/dist/index.html">Swagger</a></li>

        </ul>
    </nav>
    <div class="form">
        <form method="POST" action="pridat.php">
            <label for="den">Deň:</label><br>
            <input type="text" id="den" name="den" required><br>

            <label for="od">Od:</label><br>
            <input type="text" id="od" name="od" required><br>

            <label for="do">Do:</label><br>
            <input type="text" id="do" name="do" required><br>

            <label for="predmet">Predmet:</label><br>
            <input type="text" id="predmet" name="predmet" required><br>

            <label for="akcia">Akcia:</label><br>
            <input type="text" id="akcia" name="akcia" required><br>

            <label for="miestnost">Miestnosť:</label><br>
            <input type="text" id="miestnost" name="miestnost" required><br>

            <label for="vyucujuci">Vyučujúci:</label><br>
            <input type="text" id="vyucujuci" name="vyucujuci" required><br>

            <label for="obmedzenie">Obmedzenie:</label><br>
            <input type="text" id="obmedzenie" name="obmedzenie"><br>

            <label for="kapacita">Kapacita:</label><br>
            <input type="text" id="kapacita" name="kapacita" required><br><br>

            <input type="submit" value="Vložiť">
        </form>
    </div>
</body>

</html>
<?php
$conn->close();
?>