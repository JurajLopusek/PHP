<?php
include_once 'includes/include.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nobel prizes</title>
    <link rel="stylesheet" href="index.css">
    
</head>

<body>
    <nav>
        <ul>
            <li><a href="prihlasenie.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
    <?php
    $table_name = "";
    $sql = "SELECT r.id,r.name, pr.year, co.country, c.category FROM receivers r 
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
            if ($key === 'id') {
                continue;
            }
            echo "<th>$key</th>";
        }
        echo "</tr>
                </thead>
                <tbody>";

        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            $meno = $row['name'];
            $rok = $row['year'];
            $krajina = $row['country'];
            $kategoria = $row['category'];

            echo "<tr>";
            // Vytvoriť odkaz na novú stránku s parametrom meno
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
    <main>
        <?php
        session_start();
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            echo '<p>Nie ste prihlaseny, prosim <a href="prihlasenie.php">prihlaste sa</a> alebo sa <a href="register.php">zaregistrujte</a>.</p>';
        } else {
            echo '<h3>Vitaj ' . $_SESSION['fullname'] . ' </h3>';
            echo '<a href="restricted.php">Zabezpecena stranka</a>';
        }
        ?>
    </main>
</body>
<script>$(document).ready(function() {
        $('#example').DataTable();
    });</script>

</html>