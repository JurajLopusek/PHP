<?php

// Pripojenie k databáze
$dbSeverName = "localhost";
$dbUsername = "";
$dbPassword = "";
$dbName = "";

$conn = mysqli_connect($dbSeverName, $dbUsername, $dbPassword, $dbName);

// Kontrola pripojenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Údaje pre prihlásenie do AISu
$username = 'xlopusek';
$password = 'Juraj2001.lopusek';
$aisId = '115338';

$login_url = "https://is.stuba.sk/system/login.pl";

$rozvrh_url = 'https://is.stuba.sk/auth/katalog/rozvrhy_view.pl?rozvrh_student_obec=1?zobraz=1;format=list;rozvrh_student=' . $aisId . ';lang=sk';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'lang=sk&login_hidden=1&auth_2fa_type=no&credential_0=' . $username . '&credential_1=' . $password);
curl_setopt($ch, CURLOPT_COOKIEJAR, realpath('cookie.txt'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_exec($ch);

curl_setopt($ch, CURLOPT_URL, $rozvrh_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'rozvrh_student_obec=1&zobraz=1&format=list');

$content = curl_exec($ch);

curl_close($ch);

$dom = new DOMDocument();
@$dom->loadHTML($content);

$tables = $dom->getElementsByTagName('table');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tables = $dom->getElementsByTagName('table');
foreach ($tables as $table) {
    if ($table->getAttribute('id') === 'tmtab_1') {
        $rows = $table->getElementsByTagName('tr');
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            $rowData = array();
            foreach ($cells as $cell) {
                $rowData[] = $cell->nodeValue;
            }
            if (!empty($rowData)) {
                $den = isset($rowData[0]) ? mysqli_real_escape_string($conn, $rowData[0]) : '';
                $od = isset($rowData[1]) ? mysqli_real_escape_string($conn, $rowData[1]) : '';
                $do = isset($rowData[2]) ? mysqli_real_escape_string($conn, $rowData[2]) : '';
                $predmet = isset($rowData[3]) ? mysqli_real_escape_string($conn, preg_replace('/\s*\([^)]*\)/', '', $rowData[3])) : '';
                $akcia = isset($rowData[4]) ? mysqli_real_escape_string($conn, $rowData[4]) : '';
                $miestnost = isset($rowData[5]) ? mysqli_real_escape_string($conn, preg_replace('/\s*\([^)]*\)/', '', $rowData[5])) : '';
                $vyucujuci = isset($rowData[6]) ? mysqli_real_escape_string($conn, $rowData[6]) : '';
                $obmedzenie = isset($rowData[7]) ? mysqli_real_escape_string($conn, $rowData[7]) : '';
                $kapacita = isset($rowData[8]) ? mysqli_real_escape_string($conn, $rowData[8]) : '';

                $sql_check = "SELECT * FROM rozvrh WHERE den = '$den' AND od = '$od' AND do = '$do' AND Predmet = '$predmet' AND Akcia = '$akcia' AND Miestnost = '$miestnost' AND Vyucujuci = '$vyucujuci' AND Obmedzenie = '$obmedzenie' AND Kapacita = '$kapacita'";
                $result = $conn->query($sql_check);

                if ($result->num_rows == 0) {
                    $sql = "INSERT INTO rozvrh (den, od, do, Predmet, Akcia, Miestnost, Vyucujuci, Obmedzenie, Kapacita) VALUES ('$den', '$od', '$do', '$predmet', '$akcia', '$miestnost', '$vyucujuci', '$obmedzenie', '$kapacita')";
                    if ($conn->query($sql) !== TRUE) {
                        echo "Error: " . $sql . "<br>" . $conn->error;

                    } else {
                        header("location: index.php");
                    }
                } else {
                    echo "<script>document.getElementById('get_data').disabled = true;</script>";
                    header("location: index.php");
                    
                }
            }
        }
        break;
    }
}
$conn->close();
?>