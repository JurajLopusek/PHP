<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="prihlasenie.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: restricted.php");
        exit;
    }
    include_once 'includes/include2.php';
    require_once 'PHPGangsta/GoogleAuthenticator.php';
    $message = '';
    function validateUsername($username)
    {
        if (strlen($username) < 2) {
            return "Meno musí obsahovať aspoň 2 znaky!";
        } else {
            return "";
        }
    }
    function validatePassword($password)
    {
        if (strlen($password) < 1) {
            return "Vyplnte toto pole!";
        } else {
            return "";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = "SELECT fullname, email, username, password,created_at, twofa_code FROM users WHERE username = :username";

        $stmt = $pdo->prepare($sql);
        $message = "";
        $stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
        $stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);

        $username = $_POST["username"];
        $username_err = validateUsername($username);

        $password = $_POST["password"];
        $password_err = validatePassword($password);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                $hashed_password = $row["password"];

                if (password_verify($_POST['password'], $hashed_password)) {
                    $g2fa = new PHPGangsta_GoogleAuthenticator();
                    if ($g2fa->verifyCode($row["twofa_code"], $_POST['2fa'], 2)) {

                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $row['username'];
                        $_SESSION["fullname"] = $row['fullname'];
                        $_SESSION["email"] = $row['email'];
                        $_SESSION["created_at"] = $row['created_at'];
                        


                        header("location: restricted.php");
                    } else {
                        $message = "Neplatny kod 2FA!";
                    }
                } else {
                    $message = "Nespravne meno alebo heslo!";
                }
            } else {
                $message = "Nespravne meno alebo heslo!";
            }
        } else {
            $message = "Ups. Nieco sa pokazilo!";
        }

        unset($stmt);
        unset($pdo);
    }
    ?>
    <main>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form">
                <div class="form1">
                    <div class="title">Welcome</div>
                    <div class="subtitle">Let's sign in!</div>
                    <div class="input-container ic1">
                        <input type="text" class="input <?php if (isset($username_err))
                            echo 'invalid'; ?>" name="username" value="" id="username" placeholder=" " />
                        <div class="cut"></div>
                        <label for="username" class="placeholder">Username</label>
                        <?php if (isset($username_err)) { ?>
                            <p class="error">
                                <?php echo $username_err; ?>
                            </p>
                        <?php } ?>
                    </div>
                    <div class="input-container ic2">
                        <input type="password" class="input <?php if (isset($password_err))
                            echo 'invalid'; ?>" name="password" value="" id="password" placeholder=" " />
                        <div class="cut"></div>
                        <label for="password" class="placeholder">Password</label>
                        <?php if (isset($password_err)) { ?>
                            <p class="error">
                                <?php echo $password_err; ?>
                            </p>
                        <?php } ?>

                    </div>
                    <div class="input-container ic3">
                        <input type="number" class="input" name="2fa" value="" id="2fa" id="password" placeholder=" " />
                        <div class="cut"></div>
                        <label for="number" class="placeholder">2fa-code</label>
                    </div>
                    <p>
                        <?php echo $message; ?>
                    </p>
                    <button type="submit" class="submit">Prihlasit sa</button>
                </div>
                <hr class="separator">
                <div class="form2">
                    <div class="google">
                        <div class="title">Sign in with Google</div>
                        <div class="google-foto"><a href="google.php"><img src="images/login.png" alt="Google" width="300" height="60"></a>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <p>Este nemate vytvorene konto? <a href="register.php" class="register">Registrujte sa tu.</a></p>
    </main>
</body>

</html>