<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Osoby</title>
    <link rel="stylesheet" href="detail.css">
</head>

<body>
    <nav>
        <div class="nav-container">
            <?php
            session_start();
            include_once 'includes/include.php';
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                echo '<div class="welcome"><h3>' . $_SESSION['fullname'] . '</h3></div>';
            } elseif (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                echo '<div class="welcome"><h3>' . $_SESSION['fullname'] . '</h3></div>';
            } else {
                echo '';
            }
            ?>
            <div class="menu-toggle">
                <div class="hamburger"></div>
            </div>
            <ul>
                <?php

                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $sql = "SELECT r.id, r.name,r.surname FROM receivers r
                WHERE r.id = $id";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $meno = $row['name'] . ' ' . $row['surname'];
                    echo "<li><a class='cele_meno' href='modify_row.php?id={$row['id']}'>MODIFY</a></li>";
                    echo "<li><a class='cele_meno' href='remove_row.php?id={$row['id']}'>REMOVE</a></li>";
                }   

                ?>
            </ul>
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
        </div>



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
                    echo "<h3 class='meno'>" . $row['name'] . ' ' . $row['surname'] . "</h2>";
                    echo "<p><strong>Rok narodenia:</strong> " . $row['birth'] . "</p>";
                    echo "<p><strong>Rok umrtia:</strong> " . $row['death'] . "</p>";
                    echo "<p><strong>Pohlavie:</strong> " . $row['sex'] . "</p>";
                    echo "<p><strong>Krajina narodenia:</strong> " . $row['country'] . "</p>";

                } else {
                    echo "Osoba nebola nájdená.";
                }
            } else {
                echo "Neplatný prístup.";
            }
            ?>
        </div>
        <div class="detail2">
            <h4>Ocenenia:</h4>
            <?php
            include_once 'includes/include.php';
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                $sql2 = "SELECT c.category, p.year, p.contribution_sk, prd.language_sk,prd.genre_sk 
            FROM prizes p 
            LEFT JOIN prize_details prd ON prd.id = p.prize_details_id
            JOIN categories c ON c.id = p.category_id
            WHERE p.person_id = $id";
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        echo "<p><strong>Kategória:</strong> " . $row2['category'] . "</p>";
                        echo "<p><strong>Rok ziskania ceny:</strong> " . $row2['year'] . "</p>";
                        echo "<p><strong>Kontribúcia:</strong> " . $row2['contribution_sk'] . "</p>";
                        echo "<p><strong>Žáner:</strong> " . $row2['genre_sk'] . "</p>";
                        echo "<p><strong>Jazyk:</strong> " . $row2['language_sk'] . "</p>";
                        echo "<hr>";
                    }
                } else {
                    echo "Osoba nebola nájdená.";
                }
            } else {
                echo "Neplatný prístup.";
            }

            $conn->close();
            ?>
        </div>

    </div>
    <script src="script.js"></script>
</body>

</html>