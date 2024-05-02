<?php
$dbSeverName = "localhost";
$dbUsername = "";
$dbPassword = "";
$dbName = "";

$conn = new mysqli($dbSeverName, $dbUsername, $dbPassword, $dbName);

$id = $_GET['id'];

$den = $od = $do = $predmet = $akcia = $miestnost = $vyucujuci = $obmedzenie = $kapacita = '';

if ($id) {

    $stmt = $conn->prepare("SELECT * FROM rozvrh WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        $den = $row['den'];
        $od = $row['od'];
        $do = $row['do'];
        $predmet = $row['predmet'];
        $akcia = $row['akcia'];
        $miestnost = $row['miestnost'];
        $vyucujuci = $row['vyucujuci'];
        $obmedzenie = $row['obmedzenie'];
        $kapacita = $row['kapacita'];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $den = $_POST['new_den'];
    $od = $_POST['new_od'];
    $do = $_POST['new_do'];
    $predmet = $_POST['new_predmet'];
    $akcia = $_POST['new_akcia'];
    $miestnost = $_POST['new_miestnost'];
    $vyucujuci = $_POST['new_vyucujuci'];
    $obmedzenie = $_POST['new_obmedzenie'];
    $kapacita = $_POST['new_kapacita'];

    if ($den && $od && $do && $predmet && $akcia && $miestnost && $vyucujuci && $kapacita) {
        $data = [
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
        echo $id;
        echo '1595';
        $url = "https://node71.webte.fei.stuba.sk/Zadanie2/api.php?id=$id";
        $options = [
            'http' => [
                'method' => 'PUT',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result !== false) {
            header('Location: index.php');
            exit;
        }
    }

}
?>

<head>
    <link rel="stylesheet" type="text/css" href="edit.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
</head>

<body>
    <nav>
        <ul class="moznosti">
            <li><a href="pridat.php" title="Pridať">Pridať</a></li>
            <li><a href="z_prace.php" title="Práce">Práce</a></li>
            <li><a href="index.php" title="Domov">Domov</a></li>
            <li><a href="swagger-ui-master/dist/index.html">Swagger</a></li>
            <li><a href="swagger-ui-master/dist/index2.html">Swagger2</a></li>
        </ul>
    </nav>
    <h2>Edit Record</h2>
    <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="den">Den:</label><br>
        <input type="text" id="new_den" name="new_den" value="<?php echo $den; ?>"><br>
        <label for="od">Od:</label><br>
        <input type="text" id="new_od" name="new_od" value="<?php echo $od ?>"><br>
        <label for="do">Do:</label><br>
        <input type="text" id="new_do" name="new_do" value="<?php echo $do ?>"><br>
        <label for="predmet">Predmet:</label><br>
        <input type="text" id="new_predmet" name="new_predmet" value="<?php echo $predmet; ?>"><br>
        <label for="akcia">Akcia:</label><br>
        <input type="text" id="new_akcia" name="new_akcia" value="<?php echo $akcia; ?>"><br>
        <label for="miestnost">Miestnost:</label><br>
        <input type="text" id="new_miestnost" name="new_miestnost" value="<?php echo $miestnost; ?>"><br>
        <label for="vyucujuci">Vyucujuci:</label><br>
        <input type="text" id="new_vyucujuci" name="new_vyucujuci" value="<?php echo $vyucujuci; ?>"><br>
        <label for="obmedzenie">Obmedzenie:</label><br>
        <input type="text" id="new_obmedzenie" name="new_obmedzenie" value="<?php echo $obmedzenie; ?>"><br>
        <label for="kapacita">Kapacita:</label><br>
        <input type="text" id="new_kapacita" name="new_kapacita" value="<?php echo $kapacita; ?>"><br><br>
        <input type="submit" value="Update Record">
    </form>
</body>



<?php
$conn->close();
?>