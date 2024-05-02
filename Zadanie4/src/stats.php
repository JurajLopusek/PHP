<!DOCTYPE html>
<html>
<head>
    <title>Weather Fetch</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">

</head>

<body>
    <header>
        <nav>
            <div>
                <h1>Weather Fetch</h1>
            </div>
            <div>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="stats.php">Statistics</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <?php
    $servername = "localhost";
    $username = "xlopusek";
    $password = "Juraj2001";
    $dbname = "zadanie4";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT mesto, stat, pocet FROM visits";
    $result = $conn->query($sql);
    $sql1 = "SELECT COUNT(*) AS visitor_count 
            FROM hasovanie 
            WHERE time >= NOW() - INTERVAL 60 MINUTE";
    $result1 = $conn->query($sql1);

    if ($result1) {
        $row = $result1->fetch_assoc();
        echo "<div class='visitor-count'>Počet návštev posledých 60 minut: " . $row["visitor_count"] . "</div>";
    } else {
        echo "Error: " . $conn->error;
    }
    ?>
    
    <table>
    <h2>Tabuľka časov</h2>

        <tr>
            <th>Time Interval</th>
            <th>Visitor Count</th>
        </tr>
        <?php
        $time_intervals = array(
            "06:00-15:00",
            "15:00-21:00",
            "21:00-24:00",
            "00:00-06:00"
        );

        foreach ($time_intervals as $interval) {
            list($start, $end) = explode("-", $interval);
            $sql2 = "SELECT COUNT(*) AS visitor_count 
                    FROM hasovanie 
                    WHERE TIME(`time`) >= '$start' AND TIME(`time`) < '$end'";
            $result2 = $conn->query($sql2);

            if ($result2) {
                $row = $result2->fetch_assoc();
                echo "<tr>";
                echo "<td>$interval</td>";
                echo "<td>" . $row["visitor_count"] . "</td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='2'>Error: " . $conn->error . "</td></tr>";
            }
        }
        ?>
        
    <table id="myTable" class="table table-striped table-bordered">
    <h2>Tabuľka náštev</h2>

    <thead>
        <tr>
            <th>Mesto</th>
            <th>Štát</th>
            <th>Počet návštev</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["mesto"]."</td>";
                echo "<td>".$row["stat"]."</td>";
                echo "<td>".$row["pocet"]."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Žiadne údaje k zobrazeniu</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
</body>

</html>

