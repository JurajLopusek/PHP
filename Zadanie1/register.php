<?php
require_once 'PHPGangsta/GoogleAuthenticator.php';
include_once 'includes/include.php';
include_once 'includes/include2.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function validateEmail($email)
{
    $pattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    return preg_match($pattern, $email);
}
function validateUsername($username)
{
    if (empty($username)) {
        return true;
    }
    return false;
}
function validateLength($login)
{
    $minLength = 3;
    $maxLength = 20;
    if (strlen($login) < $minLength || strlen($login) > $maxLength) {
        return true;
    }
    return false;
}
function validate($login)
{
    if (!preg_match("/^\S+$/", $login)) {
        return true; 
    }
    return false; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $login = $_POST["username"];
    $password = $_POST["password"];
    $errmsgName = "";
    $errmsgEmail = "";
    $errmsgLastname = "";
    $errmsgLogin = "";
    $errmsgPassword = "";

    if (empty($name)) {
        $errmsgName .= "<p>Zadajte meno.</p>";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errmsgName .= "<p>Meno môže obsahovať iba písmená a medzery.</p>";
    } elseif (strlen($name) < 2) {
        $errmsgName .= "<p>Meno musí obsahovať minimálne 2 znaky.</p>";
    }

    if (empty($lastname)) {
        $errmsgLastname .= "<p>Zadajte priezvisko.</p>";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $lastname)) {
        $errmsgLastname .= "<p>Priezvisko môže obsahovať iba písmená a medzery.</p>";
    } elseif (strlen($lastname) < 2) {
        $errmsgLastname .= "<p>Priezvisko musí obsahovať min. 2 znaky.</p>";
    }

    if (empty($email)) {
        $errmsgEmail = "Emailová adresa je povinná.";
    } elseif (!validateEmail($email)) {
        $errmsgEmail = "Neplatný tvar emailovej adresy.";
    } else {
        $check_email_query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt_check_email = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt_check_email, "s", $email);
        mysqli_stmt_execute($stmt_check_email);
        mysqli_stmt_bind_result($stmt_check_email, $email_count);
        mysqli_stmt_fetch($stmt_check_email);
        mysqli_stmt_close($stmt_check_email);

        if ($email_count > 0) {
            $errmsgEmail = "Tento email už existuje.";
        }
    }

    if (validateUsername($login)) {
        $errmsgLogin = "Zadajte meno.";
    } elseif (validateLength($login)) {
        $errmsgLogin = "Meno musí mať dĺžku od 6 do 32 znakov!";
    } elseif (validate($login)) {
        $errmsgLogin = "Meno nemože osahovať prázdne znaky!";
    } else {
        $check_login_query = "SELECT COUNT(*) FROM users WHERE username = ?";
        $stmt_check_login = mysqli_prepare($conn, $check_login_query);
        mysqli_stmt_bind_param($stmt_check_login, "s", $login);
        mysqli_stmt_execute($stmt_check_login);
        mysqli_stmt_bind_result($stmt_check_login, $login_count);
        mysqli_stmt_fetch($stmt_check_login);
        mysqli_stmt_close($stmt_check_login);

        if ($login_count > 0) {
            $errmsgLogin = "Tento login už existuje.";
        }
    }

    if (empty($errmsgName) && empty($errmsgEmail) && empty($errmsgLastname) && empty($errmsgLogin)) {
        $sql = "INSERT INTO users (fullname, email, password, username, twofa_code) VALUES (?, ?, ?, ?, ?)";

        $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $username = $_POST['username'];

        $g2fa = new PHPGangsta_GoogleAuthenticator();
        $user_secret = $g2fa->createSecret();
        $codeURL = $g2fa->getQRCodeGoogleUrl('Nobel prizes', $user_secret);

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "sssss", $fullname, $email, $password, $username, $user_secret);
        $execval = mysqli_stmt_execute($stmt);
        if ($execval) {
            $qrcode = $codeURL;
            $registration_successful = true;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Document</title>
</head>
<?php

$registration_success = true;

if ($registration_success) {
    $form_hidden = true;
}
?>

<body>
    <main>
        <?php if (!isset($registration_successful) || !$registration_successful): ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form">
                    <div class="form1">
                        <div class="title">Welcome</div>
                        <div class="subtitle">Let's create your account!</div>
                        <div class="input-container ic1">
                            <input type="text" class="input <?php if (isset($errmsgName))
                                echo 'invalid'; ?>" name="firstname" value="" id="firstname" placeholder=" ">
                            <div class="cut"></div>
                            <label for="firstname" class="placeholder">Firstname</label>
                            <?php if (isset($errmsgName)) { ?>
                                <p class="error">
                                    <?php echo $errmsgName; ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="input-container ic1">
                            <input type="text" class="input <?php if (isset($errmsgLastname))
                                echo 'invalid'; ?>" name="lastname" value="" id="lastname" placeholder=" ">
                            <div class="cut"></div>
                            <label for="lastname" class="placeholder">Lastname</label>
                            <?php if (isset($errmsgLastname)) { ?>
                                <p class="error">
                                    <?php echo $errmsgLastname; ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="input-container ic1">
                            <input type="text" class="input <?php if (isset($errmsgEmail))
                                echo 'invalid'; ?>" name="email" value="" id="email" placeholder=" ">
                            <div class="cut"></div>
                            <label for="email" class="placeholder">Email</label>
                            <?php if (isset($errmsgEmail)) { ?>
                                <p class="error">
                                    <?php echo $errmsgEmail; ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="input-container ic1">
                            <input type="text" class="input <?php if (isset($errmsgLogin))
                                echo 'invalid'; ?>" name="username" value="" id="username" placeholder=" ">
                            <div class="cut"></div>
                            <label for="username" class="placeholder">Login</label>
                            <?php if (isset($errmsgLogin)) { ?>
                                <p class="error">
                                    <?php echo $errmsgLogin; ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="input-container ic1">
                            <input type="password" class="input" name="password" value="" id="password" placeholder=" ">
                            <div class="cut"></div>
                            <label for="password" class="placeholder">Password</label>
                        </div>
                        <button type="submit" class="submit">Create account</button>
                    </div>
                </div>

                <p>Already have an account? <a href="prihlasenie.php">Sign in</a></p>
            </form>
        <?php endif; ?>
        <?php if (isset($qrcode)): ?>
            <div class="qrcode-container">
                <h3 class="qrcode-message">Registrácia úspešna</h3>
                <p class="qrcode-message">Naskenujte QR kod do aplikacie Authenticator pre 2FA:</p>
                <img src="<?php echo $qrcode; ?>" alt="qr kod pre aplikaciu authenticator">
                <p class="qrcode-message">Teraz sa možete prihlásiť: <a href="prihlasenie.php" role="button">Login</a></p>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>