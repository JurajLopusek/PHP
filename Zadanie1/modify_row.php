<?php
session_start();
require 'includes/include.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST['new_name']) && isset($_POST['new_surname']) && isset($_POST['new_sex']) && isset($_POST['new_birth']) && isset($_POST['new_death'])) {
        $id = $_GET['id'];
        $new_name = $_POST['new_name'];
        $new_surname = $_POST['new_surname'];
        $new_sex = $_POST['new_sex'];
        $new_birth = $_POST['new_birth'];
        $new_death = $_POST['new_death'];
        $new_country_name = $_POST['new_country'];
        $sql_get_country_id = "SELECT id FROM countries WHERE country = '$new_country_name'";
        $result_get_country_id = $conn->query($sql_get_country_id);
        if ($result_get_country_id->num_rows > 0) {
            $row = $result_get_country_id->fetch_assoc();
            $new_country_id = $row['id'];
            $sql = "UPDATE receivers SET name='$new_name', surname='$new_surname', sex='$new_sex', birth='$new_birth', death='$new_death',country_id='$new_country_id' WHERE id=$id";
            $successUpdate = 0;
            if ($conn->query($sql) === TRUE) {
                $successUpdate = 1;
                header('location: index.php?successUpdate=' . $successUpdate . '');
                exit;
            } else {
                echo "Chyba pri aktualizácii záznamu: " . $conn->error;
            }

        } else {
            echo "Niektoré údaje chýbajú.";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Osoby</title>
    <link rel="stylesheet" href="modify.css">

</head>

<body>
    <nav>
        <?php
        include_once 'includes/include.php';
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            echo '<div class="welcome"><h3>' . $_SESSION['fullname'] . '</h3></div>';
        } elseif (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            echo '<div class="welcome"><h3>' . $_SESSION['fullname'] . '</h3></div>';
        } else {
            echo '';
        }
        ?>
        <ul>
            <?php
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                echo '<li><a href="index.php" title="Domov"><i class="fa fa-home" style="font-size:48px;color:#08d"></i></a></li>';
                echo '<li><a href="restricted.php" title="Profil"><i class="fa fa-user-circle-o" style="font-size:48px;color:#08d"></i></i></a></li>';
                echo '<li><a href="logout.php" title="Odhlásiť sa"><i class="fa fa-sign-out" style="font-size:48px;color:#08d"></i></a></li>';


            } elseif (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                echo '<li><a href="index.php" title="Domov"><i class="fa fa-home" style="font-size:48px;color:#08d"></i></a></li>';
                echo '<li><a href="restricted.php" title="Profil"><i class="fa fa-user-circle-o" style="font-size:48px;color:#08d"></i></i></a></li>';
                echo '<li><a href="logout.php" title="Odhlásiť sa"><i class="fa fa-sign-out" style="font-size:48px;color:#08d"></i></a></li>';
            } else {
                echo '<li><a href="prihlasenie.php">Login</a></li>';
                echo '<li><a href="register.php">Register</a></li>';
            }
            ?>
        </ul>

    </nav>
    <div class="main">
        <div class="detail">
            <?php
            include_once 'includes/include.php';
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT r.id,r.name,r.surname, r.sex,r.birth,r.death, pr.year, co.country, c.category FROM receivers r 
            JOIN prizes pr ON pr.person_id = r.id
            JOIN countries co ON co.id = r.country_id
            JOIN categories c ON  c.id= pr.category_id
            WHERE r.id = $id";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    echo "<h3>Úprava údajov:</h3>";
                    echo "<form class='main' action=''  method='POST'>";
                    echo "<input type='hidden' name='id' value='$id'>";
                    echo "<label for='new_name' class='label'>Nové meno:</label>";
                    echo "<input type='text' class='input' id='new_name' name='new_name' value='" . $row['name'] . "'><br>";
                    echo "<label for='new_surname'>Nové priezvisko:</label>";
                    echo "<input type='text' class='input' id='new_surname' name='new_surname' value='" . $row['surname'] . "'><br>";
                    echo "<label for='new_sex'>Nové pohlavie:</label>";
                    echo "<input type='text' class='input' id='new_sex' name='new_sex' value='" . $row['sex'] . "'><br>";
                    echo "<label for='new_birth'>Nový rok narodenia:</label>";
                    echo "<input type='number' class='input' id='new_birth' name='new_birth' value='" . $row['birth'] . "'><br>";
                    echo "<label for='new_death'>Nový rok úmrtia:</label>";
                    echo "<input type='number' class='input' id='new_death' name='new_death' value='" . $row['death'] . "'><br>";
                    echo "<label for='new_country'>Nová krajina:</label>";
                    echo "<input type='text' class='input' id='new_country' name='new_country' value='" . $row['country'] . "'><br>";
                    echo "<button class='button'>Uložiť zmeny</button>";
                    echo "</form>";
                } else {
                    echo "Osoba nebola nájdená.";
                }
            } else {
                echo "Neplatný prístup.";
            }
            ?>
        </div>
        <div id="toastBox"></div>
    </div>
    
</body>

</html>