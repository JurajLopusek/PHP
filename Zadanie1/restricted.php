<?php

session_start();

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'])) {

    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $fullname = $_SESSION['fullname'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];

} elseif ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
    $email = $_SESSION['email'];
    $id = $_SESSION['username'];
    $fullname = $_SESSION['fullname'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
} else {
    header('Location: index.php');
}


?>
<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="restricted.css">
    <title>Login/register s 2FA - Zabezpecena stranka</title>
</head>

<body>
    <nav>
        <div class="welcome">
            <h3>Vitaj
                <?php echo $_SESSION['fullname']; ?>
            </h3>
        </div>
        <ul class="moznosti">
            <li><a href="pridat.php" title="Pridať">Pridať</a></li>
        </ul>
        <ul>
            <li><a href="index.php" title="Domov"><i class="fa fa-home" style="font-size:48px;color:#08d"></i></a></li>
            <li><a href="restricted.php" title="Profil"><i class="fa fa-user-circle-o"
                        style="font-size:48px;color:#08d"></i></i></a></li>
            <li><a href="logout.php" title="Odhlásiť sa"><i class="fa fa-sign-out"
                        style="font-size:48px;color:#08d"></i></a></li>
        </ul>

    </nav>
    <main>
        <div class="main">
            <div class="foto"><i class="fa fa-user-circle-o" style="font-size:100px"></i></div>
            <div class="info">
                <div class="email">
                    <?php echo $email ?>
                </div>
                <div class="text">
                    <?php echo 'Ahoj, ' . $fullname ?>
                </div>
                <div class="text2">
                    <p>Tvoj identifikator (login) je:
                        <?php echo $id ?>
                    </p>
                </div>
            </div>
        </div>

    </main>
</body>

</html>