<?php
$login=$_SESSION["loggedin"];  

if(!$login){
    ?>
    <script> window.location.href = "index.php";</script>

    <?php
}
?>