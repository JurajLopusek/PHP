<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
    <title>Detail práce</title>
</head>

<body>
    <nav>
        <ul class="moznosti">
            <li><a href="pridat.php" title="Pridať">Pridať</a></li>
            <li><a href="z_prace.php" title="Práce">Práce</a></li>
            <li><a href="index.php" title="Domov">Domov</a></li>
            <li><a href="swagger-ui-master/dist/index.html">Swagger</a></li>

        </ul>
    </nav>
    <h1>Detail práce</h1>
    <?php
    if (isset($_GET['detail'])) {
        $id = $_GET['detail'];
        $pracovisko = $_GET['pracovisko'];
        $url = "https://is.stuba.sk/pracoviste/prehled_temat.pl?detail=$id;pracoviste=$pracovisko;lang";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
    }
    if ($response === false) {
        echo "Chyba: " . curl_error($ch);
    } else {
        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);
        $tables = $xpath->query('//table');
        if ($tables->length > 0) {
            $firstTable = $tables->item(0);
            echo "<table id='table' border='1' >";
            foreach ($firstTable->getElementsByTagName('tr') as $row) {
                echo "<tr>";
                foreach ($row->getElementsByTagName('td') as $cell) {
                    echo "<td>" . $cell->nodeValue . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }

    curl_close($ch);

    ?>
    <script>
        $(document).ready(function () {
            var table = $('#table').DataTable({
                "columnDefs": [{
                    "targets": [0, 4, 6, 7, 8, 9, 10],
                    "visible": false,
                },
                {
                    "targets": "_all",
                }
                ]
            });

            $('#skolitel').change(function () {
                var selectedValue = $(this).val();

                if (selectedValue === "Všetky") {
                    table.column(3).search('').draw(); 
                } else {
                    table.column(3).search(selectedValue).draw();
                }
            });

            $('#program').change(function () {
                var selectedValue = $(this).val();

                if (selectedValue === "Všetky") {
                    table.column(5).search('').draw(); 
                } else {
                    table.column(5).search(selectedValue).draw();
                }
            });
        });

    </script>
</body>

</html>