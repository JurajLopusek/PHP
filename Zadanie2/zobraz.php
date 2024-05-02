<?php
$dbSeverName = "localhost";
$dbUsername = "";
$dbPassword = "";
$dbName = "";

try {
    $url = "https://node71.webte.fei.stuba.sk/Zadanie2/api.php";
    $response = file_get_contents($url);
    if ($response === false) {
        echo 'Chyba při vykonávání požadavku: ' . error_get_last()['message'];
    } else {
        $schedule = json_decode($response, true);

        echo "<table border='1'>";
        echo "<tr><th>Den</th><th>Od</th><th>Do</th><th>Predmet</th><th>Akcia</th><th>Miestnost</th><th>Vyucujuci</th><th>Obmedzenie</th><th>Kapacita</th><th>Delete</th><th>Edit</th></tr>";
        foreach ($schedule as $row) {
            echo "<tr>";
            echo "<td>" . $row['den'] . "</td>";
            echo "<td>" . $row['od'] . "</td>";
            echo "<td>" . $row['do'] . "</td>";
            echo "<td>" . $row['predmet'] . "</td>";
            echo "<td>" . $row['akcia'] . "</td>";
            echo "<td>" . $row['miestnost'] . "</td>";
            echo "<td>" . $row['vyucujuci'] . "</td>";
            echo "<td>" . $row['obmedzenie'] . "</td>";
            echo "<td>" . $row['kapacita'] . "</td>";
            echo "<td><a href='delete.php?id=" . $row['id'] . "'>Delete</a></td>";
            echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "Chyba: " . $e->getMessage();
}
$conn = null;
?>