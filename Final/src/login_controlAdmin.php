<?php
$login=$_SESSION["loggedin"];  

if(!$login){
    ?>
    <script> window.location.href = "index.php";</script>

    <?php
}else{
    $role = $_SESSION["role"];
    if($role!="admin"){
        ?>
    <script> window.location.href = "index.php";</script>

    <?php
    }
}
?>