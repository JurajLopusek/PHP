<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Získanie údajov z AISu</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/sweetalert/dist/sweetalert.css">
</head>

<body>
    <nav>
        <ul class="moznosti">
            <li><a href="pridat.php" title="Pridať">Pridať</a></li>
            <li><a href="z_prace.php" title="Práce">Práce</a></li>
            <li><a href="index.php" title="Domov">Domov</a></li>
            <li><a href="swagger-ui-master/dist/index.html">Swagger</a></li>
            <li><a href="swagger-ui-master/dist/index2.html">Swagger2</a></li>
        </ul>
    </nav>
    <form method="post" action="get_ais_data.php">
        <input type="submit" id="get_data" class="btn btn-info btn-lg" name="get_data" value="Vlozit udaje do databazy">
    </form>
    <button onclick="vymazat()" id="button" class="btn btn-info btn-lg">Vymaž</button>
    <button onclick="zobraz()" id="button" class="btn btn-info btn-lg">Zobraz rozvrh</button>



    <div id="vysledok"></div>


    <script src="script.js"></script>

</body>

</html>