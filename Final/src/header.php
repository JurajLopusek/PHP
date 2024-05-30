<?php
require 'configFinal.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $stmt = $conn->prepare("SELECT role FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();
    $user_id = $_SESSION["user_id"];
    $_SESSION["role"] = $role;
}
function isActive($navLink)
{
    $currentLink = basename($_SERVER['REQUEST_URI'], ".php");

    // Check if the navigation link is in the current URL
    if (strpos($currentLink, $navLink) !== false) {
        return 'active';  // Return the 'active' class
    } else {
        return '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOTE</title>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script type="text/javascript"
        src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <link rel="icon" type="image/png" href="images/vote.png">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="images/vote.png" alt="Vote System Logo">
            </a>
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navbar links -->

            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <div class="form-wrapper">
                        <form class="d-flex" action="questionDetail.php" method="get" id="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search Q" aria-label="Search"
                                name="questionCode">
                            <button class="btn btn-outline-light" localize="search" type="submit"></button>
                        </form>
                    </div>
                    <a class="nav-link <?php echo isActive('index'); ?> " localize="home" aria-current="page"
                        href="index.php"></a>
                        <a class="nav-link <?php echo isActive('manual'); ?> " localize="manual" aria-current="page"
                        href="manual.php"></a>
                    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <a class="nav-link <?php echo isActive('addQuestion'); ?>" aria-current="page" localize="add_q"
                            href="addQuestion.php"></a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo htmlspecialchars($_SESSION["username"]); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <?php if ($role === 'admin') { ?>
                                    <li><a class="dropdown-item" localize="user_man" href="user_managment.php"></a></li>
                                <?php } ?>
                                <li><a class="dropdown-item" localize="acc_settings" href="account_settings.php"></a></li>
                                <li><a class="dropdown-item" localize="log_out" href="controllers/logout.php"></a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Login Modal Trigger -->
                        <button class="btn btn-outline-light btn-lg" data-bs-toggle="modal" localize="log_in"
                            data-bs-target="#loginModal"></button>
                    <?php endif; ?>
                    <div class="select-language">
                        <button class="language-btn" data-lang="en" id="en" onclick="setLanguagePreference('en')"><svg
                                xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 22 22"
                                class="Flag_flag_1I8Yz MultistoreSwitch_flag_3upTB">
                                <path opacity=".1" d="M0 3h22v16H0z"></path>
                                <path fill="#00247d" fill-rule="evenodd" d="M1 17.999h20V4H1v13.999z"></path>
                                <path fill="#fff" fill-rule="evenodd"
                                    d="M18.764 4l-6.098 4.267V4H9.333v4.267L3.236 4H1v1.565l4.43 3.101H1v4.666h4.43L1 16.433V18h2.236l6.097-4.269V18h3.333v-4.269L18.764 18H21v-1.567l-4.431-3.101H21V8.666h-4.431L21 5.565V4h-2.236z">
                                </path>
                                <path fill="#cf142b" fill-rule="evenodd" d="M10 4v5.6H1v2.8h9V18h2v-5.6h9V9.6h-9V4h-2z">
                                </path>
                                <path fill="#cf142b" fill-rule="evenodd"
                                    d="M1 4v1.043l5.176 3.623h1.491L1 4zM14.333 13.333L20.999 18v-1.044l-5.176-3.623h-1.49zM19.509 4l-6.667 4.666h1.499l6.658-4.66V4h-1.49zM7.675 13.333L1.009 18h1.482l6.666-4.667H7.675z">
                                </path>
                            </svg></button>
                        <button class="language-btn" data-lang="sk" id="sk" onclick="setLanguagePreference('sk')"><svg
                                xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 22 22"
                                class="Flag_flag_1I8Yz MultistoreSwitch_flag_3upTB">
                                <path opacity=".1" d="M0 3h22v16H0z"></path>
                                <path fill="#fff" fill-rule="evenodd" d="M1 18h20V4H1v14z"></path>
                                <path fill="#0b4ea2" fill-rule="evenodd" d="M1 18h20V8.666H1V18z"></path>
                                <path fill="#ee1c25" fill-rule="evenodd" d="M1 18h20v-4.667H1V18z"></path>
                                <path
                                    d="M7.38 14.815a4.685 4.685 0 01-3.05-4.177c0-2.374.112-3.453.112-3.453h5.874s.113 1.079.113 3.453a4.685 4.685 0 01-3.05 4.177"
                                    fill="#fff" fill-rule="evenodd"></path>
                                <path
                                    d="M7.38 14.5a4.3 4.3 0 01-2.799-3.833c0-2.177.104-3.167.104-3.167h5.388s.104.99.104 3.167A4.3 4.3 0 017.38 14.5"
                                    fill="#ee1c25" fill-rule="evenodd"></path>
                                <path
                                    d="M7.626 10.097a4.483 4.483 0 001.46-.164s-.013.194-.013.419.014.42.014.42a4.688 4.688 0 00-1.461-.165v1.202h-.493v-1.202a4.688 4.688 0 00-1.461.165s.014-.194.014-.42-.014-.42-.014-.42a4.483 4.483 0 001.46.165v-.755a3.44 3.44 0 00-1.155.166s.014-.194.014-.42-.014-.42-.014-.42a3.43 3.43 0 001.155.166 6.647 6.647 0 00-.154-1.081s.287.023.4.023.403-.023.403-.023a6.76 6.76 0 00-.154 1.08 3.43 3.43 0 001.155-.165s-.014.194-.014.42.014.42.014.42a3.44 3.44 0 00-1.156-.166z"
                                    fill="#fff" fill-rule="evenodd"></path>
                                <path
                                    d="M7.38 11.679c-.58 0-.89.804-.89.804a.71.71 0 00-.647-.381.915.915 0 00-.706.548A5.315 5.315 0 007.38 14.5a5.31 5.31 0 002.242-1.85.911.911 0 00-.706-.548.712.712 0 00-.646.38s-.31-.803-.89-.803"
                                    fill="#0b4ea2" fill-rule="evenodd"></path>
                            </svg></button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
<!-- Login/Register Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" localize="account_acces" id="loginModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nav tabs for login and registration forms -->
                    <ul class="nav nav-tabs" id="accountTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active modalButtons" id="login-tab" data-bs-toggle="tab"
                                data-bs-target="#login" type="button" localize="log_in_button" role="tab"
                                aria-controls="login" aria-selected="true"></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link modalButtons" id="register-tab" data-bs-toggle="tab"
                                data-bs-target="#register" type="button" role="tab" localize="signup_button"
                                aria-controls="register" aria-selected="false"></button>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content" id="accountTabsContent">
                        <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                            <!-- Login Form -->
                            <form action="controllers/login.php" method="post">
                                <div class="mb-3">
                                    <label for="username" localize="username" class="form-label"></label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" localize="pwd" class="form-label"></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" localize="log_in_button" class="btn btn-primary"></button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <!-- Registration Form -->
                            <form action="controllers/register.php" method="post" onsubmit="return validatePassword();">
                                <div class="mb-3">
                                    <label for="username" localize="username" class="form-label"></label>
                                    <input type="text" class="form-control" id="username" name="username">
                                </div>
                                <div class="mb-3">
                                    <label for="email" localize="email" class="form-label"></label>
                                    <input type="text" class="form-control" id="email" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="password" localize="pwd" class="form-label"></label>
                                    <input type="password" class="form-control" id="passwordReg" name="passwordReg">
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" localize="confirm_pwd" class="form-label"></label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password">
                                </div>
                                <button type="submit" localize="signup_button" class="btn btn-primary"></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>