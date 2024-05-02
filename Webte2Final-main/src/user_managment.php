<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'header.php';
require '../../../configFinal.php';
?>

<div class="containerTable">
    <div class="tableHeader">
        <h2 class="text" id="text" localize="admin_text"></h2>
        <h2 class="text" id="userDataTitle" localize="user_text" style="display: none;"></h2>
        <h2 class="text" id="userDataTitle2" style="display: none;"></h2>

        <?php if ($role === "admin") { ?>
            <label for="userFilter" localize="user"></label>
            <select id="userFilter" class="filterselect" onchange="filterTableAdmin()">
                <option value="" localize="all_filter"></option>
                <?php
                $sql_users = "SELECT DISTINCT username FROM Users";
                $stmt_users = $conn->prepare($sql_users);
                $stmt_users->execute();
                $result = $stmt_users->get_result();
                while ($user = $result->fetch_assoc()) {
                    ?>
                    <option value="<?php echo htmlspecialchars($user['username']); ?>">
                        <?php echo htmlspecialchars($user['username']); ?>
                    </option>
                    <?php
                }
                $stmt_users->close();
                ?>
            </select>
        <?php } ?>

    </div>
    <div id="passwordColumn" style="display: none;">
        <div class="container mt-4">
            <h2 localize="change_pwd"></h2>
            <form action="" id="changePasswordForm" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label>
                        <h2 localize="new_pwd"></h2>
                    </label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    <div class="invalid-feedback" localize="enter_new_pwd"></div>
                </div>
                <div class="mb-3">
                    <label>
                        <h2 localize="conf_pwd"></h2>
                    </label>
                    <input type="password" id="confirm_password2" name="confirm_password2" class="form-control"
                        required>
                    <div class="invalid-feedback" localize="confirm_your_pwd"></div>
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <div id="roleColumn" style="display: none;">
        <div class="container mt-4">
            <h2 localize="change_role"></h2>
            <form action="" id="changeRoleForm" class="needs-validation" novalidate>
                <div class="mb-3">
                    <select id="roleSelect" name="role" class="form-select" required>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            $role = htmlspecialchars($row['role']);
                            echo "<option value=\"$role\">$role</option>";
                        }
                        $stmt->close();
                        ?>
                    </select>
                    <div class="invalid-feedback" localize="confirm_your_pwd"></div>
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="alerts.js"></script>
    <script src="script.js"></script>
    <script>
        function filterTableAdmin() {
            var selectedUser = document.getElementById("userFilter").value;
            if (selectedUser !== "") {
                document.getElementById("passwordColumn").style.display = "block";
                document.getElementById("text").style.display = "none";
                document.getElementById("userDataTitle").style.display = "block";
                document.getElementById("roleColumn").style.display = "block";
                document.getElementById("userDataTitle2").style.display = "block";

                document.getElementById("userDataTitle2").textContent = selectedUser;
            } else {
                document.getElementById("text").style.display = "block";
                document.getElementById("passwordColumn").style.display = "none";
                document.getElementById("roleColumn").style.display = "none";
                document.getElementById("userDataTitle").style.display = "none"; // Skrýt nový nadpis
                document.getElementById("userDataTitle2").style.display = "none"; // Skrýt nový nadpis

            }
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "get_user_id.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var userId = xhr.responseText;
                    console.log("User ID: " + userId);
                }
            };
            xhr.send("username=" + selectedUsername);
        }
    </script>