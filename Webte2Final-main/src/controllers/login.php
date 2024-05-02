<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../../../configFinal.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT user_id, role, password FROM Users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($user_id, $role, $hashed_password);
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["username"] = $username;   
                    $_SESSION["role"] = $role; 

                    header("location: /Webte2Final/src/index.php?action=login-success");
                } else {
                     header("location: /Webte2Final/src/index.php?action=login-failed");
                }
            } else {
                header("location: /Webte2Final/src/index.php?action=login-failed");
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
