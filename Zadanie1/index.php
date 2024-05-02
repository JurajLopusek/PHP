<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
    <link rel="stylesheet" href="index.css">
    <title>Nobel prizes</title>

</head>

<body>
    <script type=text/javascript>
        function showToast(message) {
            swal({
                title: "Úspešne "+message+" !",
                text: "Klikni na button!",
                icon: "success",
                button: "Yes!",
            });
        }
    </script>
    <div id="cookie-toast"
        style="display: none; position: fixed; bottom: 20px; left: 20px; background-color: #333; color: #fff; padding: 10px; border-radius: 5px; z-index: 1000;">
        Táto stránka používa súbory cookie na zlepšenie používateľského zážitku. <a href="#"
            onclick="acceptCookies()">Prijať</a>
    </div>
    <nav>

        <?php
        session_start();
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
    <?php
    $success = $_GET['success'];
    $successRemove = $_GET['successRemove'];
    $successUpdate= $_GET['successUpdate'];

    if ($success == 1) {
        echo '<script type=text/javascript>
                showToast("pridaný");
            </script>;';
    }
    if ($successRemove == 1) {
        echo '<script type=text/javascript>
                showToast("odstránený");
            </script>;';
    }
    if ($successUpdate == 1) {
        echo '<script type=text/javascript>
                showToast("modifikovaný");
            </script>;';
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $sql = "SELECT DISTINCT year FROM prizes";
    $result = $conn->query($sql);

    $sql2 = "SELECT DISTINCT category FROM categories";
    $result2 = $conn->query($sql2);
    ?>
    <div class="comboboxy">
        <div class="combo1">
            <label for="combobox1">Výber rok:</label>
            <select id="combobox1" onchange="filterData()">
                <option onclick="showAllData()" value="">Všetky</option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No data found</option>";
                }
                ?>
            </select>
        </div>
        <div class="combo2">
            <label for="combobox2">Výber kategóriu:</label>
            <select id="combobox2" onchange="filterData()">
                <option onclick="showAllData()" value="">Všetky</option>
                <?php
                if ($result2->num_rows > 0) {
                    while ($row = $result2->fetch_assoc()) {
                        echo "<option value='" . $row['category'] . "'>" . $row['category'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No data found</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php


    $sql = "SELECT r.id, r.name,r.surname, pr.year, co.country, c.category FROM receivers r 
            JOIN prizes pr ON pr.person_id = r.id
            JOIN countries co ON co.id = r.country_id
            JOIN categories c ON  c.id= pr.category_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table id='example' class='display' style='width:100%'>
                <thead>
                <tr>";
        $row = $result->fetch_assoc();
        foreach ($row as $key => $value) {
            if ($key === 'id' || $key === 'surname') {
                continue;
            }
            echo "<th>$key</th>";
        }
        echo "</tr>
                </thead>
                <tbody>";

        while ($row = $result->fetch_assoc()) {
            $meno = $row['name'] . ' ' . $row['surname'];
            $rok = $row['year'];
            $krajina = $row['country'];
            $kategoria = $row['category'];

            echo "<tr>";
            echo "<td><a class='cele_meno' href='detail.php?id={$row['id']}'>$meno</a></td>";
            echo "<td>$rok</td>";
            echo "<td>$krajina</td>";
            echo "<td>$kategoria</td>";
            echo "</tr>";
        }
        echo "</tbody>
              </table>";
    } else {
        echo "0 results";
    }
    $conn->close();

    ?>



</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script src="script.js"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            order: [[1, 'asc']]
        });
    });
    function filterData() {
        var year = $('#combobox1').val();
        var category = $('#combobox2').val();

        var dataTable = $('#example').DataTable();

        if (year) {
            dataTable.column(1).search(year).draw();
            dataTable.column(1).visible(false);
        } else {
            dataTable.column(1).visible(true);
        }

        if (category) {
            dataTable.column(3).search(category).draw();
            dataTable.column(3).visible(false);
        } else {
            dataTable.column(3).visible(true);
        }

    }
    function showAllData() {
        var dataTable = $('#example').DataTable();
        dataTable.search('').columns().search('').draw();
        dataTable.rows().nodes().to$().show();

        dataTable.columns().visible(true);

    }

    window.onload = function () {
        checkCookies();
        showAllData();
    };

</script>

</html>