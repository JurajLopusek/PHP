<!DOCTYPE html>
<html lang="sk">

<head>
    <link rel="stylesheet" type="text/css" href="style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="z_prace.css">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
</head>

<body>
    <nav>
        <ul class="moznosti">
            <li><a href="pridat.php" title="Pridať">Pridať</a></li>
            <li><a href="z_prace.php" title="Práce">Práce</a></li>
            <li><a href="index.php" title="Domov">Domov</a></li>
            <li><a href="swagger-ui/dist/index.html">Swagger</a></li>

        </ul>
    </nav>
    <form method="POST">
        <label for="pracovisko">Vyberte číslo pracoviska:</label>
        <select id="pracovisko" name="pracovisko">
            <option value="642">Ustav automobilovej mechatroniky 642</option>
            <option value="548">Ustav elektroenergetiky a aplikovanej elektrotechniky 548</option>
            <option value="549">Ustav elektroniky a fotoniky 549</option>
            <option value="550">Ustav elektrotechniky 550</option>
            <option value="816">Ustav informatiky a matematiky 816</option>
            <option value="817">Ustav jadrov ́eho a fyzik ́alneho inˇzinierstva 817</option>
            <option value="818">Ustav multimedialnych informacnych a komunikacnych technologií 818</option>
            <option value="356">Ustav robotiky a kybernetiky 356</option>
        </select>
        <label for="typprace">Vyberte číslo pracoviska:</label>
        <select id="typprace" name="typprace">
            <option value="0">Ziadna</option>
            <option value="1">BP</option>
            <option value="2">DP</option>
            <option value="3">DizP</option>
        </select>
        <button type="submit" name="submit">Načítať dáta</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pracovisko = $_POST['pracovisko'];
        $typprace = $_POST['typprace'];
        $url = "https://node71.webte.fei.stuba.sk/Zadanie2/api2.php?pracovisko=$pracovisko&typprace=$typprace";

        $response = file_get_contents($url);
        if ($response === false) {
            echo 'Chyba při vykonávání požadavku: ' . error_get_last()['message'];
        } else {
            $data = json_decode($response, true);
            $id = $_GET['id'] ?? null;
            $column_name = "Vedúci práce";
            $thead_columns = ["Por.", "Typ", "Názov témy", "Vedúci práce", "Garantujúce pracovisko", "Program"];

            $column_index = array_search($column_name, $thead_columns);
            echo '<div class="container">'; // Open the container div
    
            if ($column_index === false) {
                echo "Stĺpec s názvom '$column_name' nebol nájdený.";
            } else {
                echo '<div class="box">';
                echo '<label for="skolitel">Vyberte školiteľa:</label>';
                echo '<select id="skolitel" name="skolitel">';
                echo '<option value="Všetky">Všetky</option>';
                $supervisors = array_unique(array_column($data, $column_index));

                foreach ($supervisors as $supervisor) {
                    echo '<option value="' . $supervisor . '">' . $supervisor . '</option>';
                }
                echo '</select>';
                echo '</div>';
                echo '<br>';
            }

            $column_name2 = "Program";
            $column_index2 = array_search($column_name2, $thead_columns);
            if ($column_index2 === false) {
                echo "Stĺpec s názvom '$column_name2' nebol nájdený.";
            } else {
                echo '<div class="box">';
                echo '<label for="program">Vyberte program:</label>';
                echo '<select id="program" name="program">';
                echo '<option value="Všetky">Všetky</option>';

                $programs = array_unique(array_column($data, $column_index2));
                foreach ($programs as $program) {
                    echo '<option value="' . $program . '">' . $program . '</option>';
                }
                echo '</select>';
                echo '</div>';
                echo '<br>';
            }

            echo '</div>'; // Close the container div
    



            echo "<table id='table' class='display' style='width:100%'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Por.</th>";
            echo "<th>Typ</th>";
            echo "<th>Názov témy</th>";
            echo "<th>Vedúci práce</th>";
            echo "<th>Garantujúce pracovisko</th>";
            echo "<th>Program</th>";
            echo "<th>Zameranie</th>";
            echo "<th>Určené pre</th>";
            echo "<th>Podrobnosti</th>";
            echo "<th>Obsadené/Max</th>";
            echo "<th>Riešitelia</th>";
            echo "</tr>";
            echo "</thead>
                <tbody>";

            foreach ($data as $row) {
                echo '<tr>';
                foreach ($row as $key => $cell) {
                    $id = $row[8];
                    if ($key === 2) {
                        $url = "details.php?detail=$id;pracoviste=$pracovisko;lang";
                        echo '<td><a href="' . $url . '">' . $cell . '</a></td>';
                    } else {
                        echo '<td>' . $cell . '</td>';
                    }
                }
                echo '</tr>';
            }

            echo '</tbody>
            </table>';
        }
    }
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