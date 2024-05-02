<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');
$dbSeverName = "localhost";
$dbUsername = "xlopusek";
$dbPassword = "Juraj2001";
$dbName = "rozvrh";

$conn = mysqli_connect($dbSeverName, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        $sql = "SELECT * FROM rozvrh";
        $result = $conn->query($sql);

        $schedule = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $schedule[] = $row;
            }

        }
        echo json_encode($schedule);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        create1($conn, $data);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        delete_row($conn, $id);
        break;

    case 'PUT':
        $id = $_GET['id'];
        $new_data = json_decode(file_get_contents('php://input'), true);
        update($conn, $id, $new_data);
        break;
    default:
        echo json_encode(["error" => "Nepodporovaná metóda."]);
}

function delete_row($conn, $id)
{
    echo 'delete';

    if ($id) {
        echo $id;
        if (isset($id)) {
            $stmt = $conn->prepare("DELETE FROM rozvrh WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Záznam bol úspešne vymazaný."]);
            } else {
                echo json_encode(["error" => "Chyba pri vymazaní záznamu."]);
            }
        } else {
            echo json_encode(["error" => "Identifikátor záznamu nebol zadaný."]);
        }
    }
}
function update($conn, $id, $new_data)
{
    $sql = "UPDATE rozvrh SET den = ?, od = ?, do = ?, predmet = ?, akcia = ?, miestnost = ?, vyucujuci = ?, obmedzenie = ?, kapacita = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $new_data['den'], $new_data['od'], $new_data['do'], $new_data['predmet'], $new_data['akcia'], $new_data['miestnost'], $new_data['vyucujuci'], $new_data['obmedzenie'], $new_data['kapacita'], $id);
    $stmt->execute();

}
function create1($conn, $data)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    echo $data['den'];

    $stmt = $conn->prepare("INSERT INTO rozvrh (den, od, do, predmet, akcia, miestnost, vyucujuci, obmedzenie, kapacita) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param('sssssssss', $data['den'], $data['od'], $data['do'], $data['predmet'], $data['akcia'], $data['miestnost'], $data['vyucujuci'], $data['obmedzenie'], $data['kapacita']);

    $stmt->execute();

}
$conn->close();
function getTable(){
    
}
?>