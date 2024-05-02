<?php
include_once 'includes/include.php';

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsgName = "";
    $errmsgLastname = "";
    $errmsgBirth = "";
    $errmsgDeath = "";
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $organization = $_POST['organization'];
    $sex = $_POST['sex'];
    $birth = $_POST['birth'];
    $death = $_POST['death'];
    $gender = $_POST['sex'];
    $category = $_POST['category'];
    if ($category == "category") {
        $errmsgCategory = "Musíte vybrat kategoriu!.";
    }
    $errmsgCountry = "";
    $country = $_POST['country'];
    if ($country == "countries") {
        $errmsgCountry = "Musíte vybrat krajinu!.";
    }



    $errmsgSex = "";
    if (empty($name)) {
        $errmsgName .= "<p>Zadajte meno.</p>";
    } elseif (!preg_match("/^[a-zčšžôľťáéíóúäýďĺňěřůčŠŽÔĽŤÁÉÍÓÚÄÝĎĽŇĚŘŮČA-ZČŠŽÔĽŤÁÉÍÓÚÄÝĎĽŇĚŘŮČ\s]*$/u", $name)) {
        $errmsgName .= "<p>Meno môže obsahovať iba písmená a medzery.</p>";
    } elseif (strlen($name) < 2) {
        $errmsgName .= "<p>Meno musí obsahovať minimálne 2 znaky.</p>";
    }

    if (empty($surname)) {
        $errmsgLastname .= "<p>Zadajte priezvisko.</p>";
    } elseif (!preg_match("/^[a-zčšžôľťáéíóúäýďĺňěřůčŠŽÔĽŤÁÉÍÓÚÄÝĎĽŇĚŘŮČA-ZČŠŽÔĽŤÁÉÍÓÚÄÝĎĽŇĚŘŮČ\s]*$/u", $surname)) {
        $errmsgLastname .= "<p>Priezvisko môže obsahovať iba písmená a medzery.</p>";
    } elseif (strlen($surname) < 2) {
        $errmsgLastname .= "<p>Priezvisko musí obsahovať min. 2 znaky.</p>";
    }
    if (empty($birth)) {
        $errmsgBirth .= "<p>Zadajte dátum narodenia.</p>";
    } elseif (!strtotime($birth)) {
        $errmsgBirth .= "<p>Zadajte platný dátum narodenia.</p>";
    }

    if (empty($death)) {
        $errmsgDeath .= "<p>Zadajte dátum úmrtia.</p>";
    } elseif (!strtotime($death)) {
        $errmsgDeath .= "<p>Zadajte platný dátum úmrtia.</p>";
    }

    if ($birth > $death) {
        $errmsgBirth .= "<p>Musí byť menší ako dátum úmrtia.</p>";
        $errmsgDeath .= "<p>Musí byť väčší ako dátum narodenia.</p>";
    }
    if (empty($gender)) {
        $errmsgSex .= "<p>Zadajte pohlavie.</p>";
    } elseif (!($gender === "M" || $gender === "F")) {
        $errmsgSex .= "<p>Pohlavie môže obsahovať iba M/F.</p>";
    }
    $errmsgExist = "";
    $sql_check_person = "SELECT id FROM receivers WHERE name = '$name' AND surname = '$surname'";
    $result_check_person = mysqli_query($conn, $sql_check_person);
    if (mysqli_num_rows($result_check_person) > 0 && !(empty($name) && empty($lastname))) {
        $errmsgExist .= "<h2 class='BigError'>Táto osoba už existuje!!!</h2>";

    }
    if (empty($errmsgName) && empty($errmsgLastname) && empty($errmsgBirth) && empty($errmsgDeath) && empty($errmsgCountry) && empty($errmsgCategory) && empty($errmsgSex) && empty($errmsgExist)) {
        $country = mysqli_real_escape_string($conn, $country);
        $sql_check_country = "SELECT id FROM countries WHERE country = '$country'";
        $result_check_country = mysqli_query($conn, $sql_check_country);
        if (mysqli_num_rows($result_check_country) == 0) {
            $sql_insert_country = "INSERT INTO countries (country) VALUES ('$country')";
            if (mysqli_query($conn, $sql_insert_country)) {
                echo "New country added successfully.\n";
            } else {
                echo "Error: " . $sql_insert_country . "<br>" . mysqli_error($conn);
            }
        }

        $sql_people = "INSERT INTO receivers (name, surname, organization, sex, birth, death, country_id)
                   VALUES ('$name', '$surname', '$organization', '$sex', '$birth', '$death', 
                           (SELECT id FROM countries WHERE country = '$country'))";
        if (mysqli_query($conn, $sql_people)) {
            $person_id = mysqli_insert_id($conn);

            $language_sk = $_POST['language_sk'];
            $language_en = $_POST['language_en'];
            $genre_sk = $_POST['genre_sk'];
            $genre_en = $_POST['genre_en'];

            $sql_prize_detail = "INSERT INTO prize_details (language_sk, language_en, genre_sk, genre_en)
                             VALUES ('$language_sk', '$language_en', '$genre_sk', '$genre_en')";

        }
        if (mysqli_query($conn, $sql_prize_detail)) {

            $prize_details_id = mysqli_insert_id($conn);

            $year = $_POST['year'];
            $category = $_POST['category'];
            $contribution_en = $_POST['contribution_en'];
            $contribution_sk = $_POST['contribution_sk'];
            $sql_prizes = "INSERT INTO prizes (year,contribution_sk, contribution_en, person_id, category_id, prize_details_id)
                           VALUES ('$year', '$contribution_sk', '$contribution_en','$person_id',
                           (SELECT id FROM categories WHERE category = '$category'), '$prize_details_id')";
            $success = 0;
            if (mysqli_query($conn, $sql_prizes)) {
                $success = 1;
                header('location: index.php?success=' . $success . '');
                exit;
            } else {
                echo "Error1: " . mysqli_error($conn);
            }
        } else {
            echo "Error2: " . mysqli_error($conn);
        }
    }

}
?>

<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pridat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
    <title>Pridať nový prvok</title>
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
        <form action="" method="post">
            <div class="main">
                <div class="title">
                    <h2>Add Person</h2>
                </div>
                <div class="form">
                    <div class="person">
                        <div class="input-container ic1">
                            <input type="text" class="input <?php if (isset($errmsgName))
                                echo 'invalid'; ?>" name="name" value="" id="name" placeholder=" ">
                            <div class="cut"></div>
                            <label for="name" class="placeholder">Name</label>
                            <?php if (isset($errmsgName)) { ?>
                                <p class="error">
                                    <?php echo $errmsgName; ?>
                                </p>
                            <?php } ?>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="surname" name="surname" class="input <?php if (isset($errmsgLastname))
                                echo 'invalid'; ?>" placeholder=" ">
                            <div class="cut"></div>
                            <label for="surname" class="placeholder">Surname</label>
                            <?php if (isset($errmsgLastname)) { ?>
                                <p class="error">
                                    <?php echo $errmsgLastname; ?>
                                </p>
                            <?php } ?>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="organization" name="organization" class="input" placeholder=" ">
                            <div class="cut"></div>
                            <label for="organization" class="placeholder">Organization</label>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="sex" name="sex" class="input <?php if (isset($errmsgSex))
                                echo 'invalid'; ?>" placeholder=" ">
                            <div class="cut"></div>
                            <label for="sex" class="placeholder">Sex</label>
                            <?php if (isset($errmsgSex)) { ?>
                                <p class="error">
                                    <?php echo $errmsgSex; ?>
                                </p>
                            <?php } ?>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="birth" name="birth" class="input <?php if (isset($errmsgBirth))
                                echo 'invalid'; ?>" placeholder=" ">
                            <div class="cut"></div>
                            <label for="birth" class="placeholder">Birth</label>
                            <?php if (isset($errmsgBirth)) { ?>
                                <p class="error">
                                    <?php echo $errmsgBirth; ?>
                                </p>
                            <?php } ?>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="death" name="death" class="input <?php if (isset($errmsgDeath))
                                echo 'invalid'; ?>" placeholder=" ">
                            <div class="cut"></div>
                            <label for="death" class="placeholder">Death</label>
                            <?php if (isset($errmsgDeath)) { ?>
                                <p class="error">
                                    <?php echo $errmsgDeath; ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="input-container ic1">
                            <?php
                            $sql = "SELECT DISTINCT country FROM countries";
                            $result = $conn->query($sql);
                            ?>
                            <select id="country" name="country" class="input <?php if (isset($errmsgCountry))
                                echo 'invalid'; ?>">
                                <option value="countries">Countries</option>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option  id='country' name='country' value='" . $row['country'] . "'>" . $row['country'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                            <?php if (isset($errmsgCountry)) { ?>
                                <p>
                                    <?php echo $errmsgCountry; ?>
                                </p>
                            <?php } ?>
                        </div>
                    </div>


                    <div class="detail">
                        <div class="input-container ic1">
                            <input type="text" id="language_sk" name="language_sk" class="input" placeholder=" ">
                            <div class="cut"></div>
                            <label for="language_sk" class="placeholder">Language (SK)</label>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="language_en" name="language_en" class="input" placeholder=" ">
                            <div class="cut"></div>
                            <label for="language_en" class="placeholder">Language (EN)</label>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="genre_sk" name="genre_sk" class="input" placeholder=" ">
                            <div class="cut"></div>
                            <label for="genre_sk" class="placeholder">Genre (SK)</label>
                        </div>

                        <div class="input-container ic2">
                            <input type="text" id="genre_en" name="genre_en" class="input" placeholder=" ">
                            <div class="cut"></div>
                            <label for="genre_en" class="placeholder">Genre (EN)</label>
                        </div>
                    </div>

                    <div class="prizes">
                        <div class="input-container ic1">
                            <input type="text" id="year" name="year" class="input" placeholder=" ">
                            <div class="cut"></div>
                            <label for="year" class="placeholder">Year</label>
                        </div>

                        <div class="input-container ic1">
                            <?php
                            $sql2 = "SELECT DISTINCT category FROM categories";
                            $result2 = $conn->query($sql2);
                            ?>
                            <select id="category" name="category" class="input <?php if (isset($errmsgCategory))
                                echo 'invalid'; ?>">
                                <option value="category">Category</option>
                                <?php
                                if ($result2->num_rows > 0) {
                                    while ($row = $result2->fetch_assoc()) {
                                        echo "<option  id='category' name='category' value='" . $row['category'] . "'>" . $row['category'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                            <?php if (isset($errmsgCategory)) { ?>
                                <p>
                                    <?php echo $errmsgCategory; ?>
                                </p>
                            <?php } ?>
                        </div>

                        <div class="input-container ic1">
                            <input type="text" id="contribution_en" name="contribution_en" class="input"
                                placeholder=" ">
                            <div class="cut"></div>
                            <label for="contribution_en" class="placeholder">Contribution (EN)</label>
                        </div>

                        <div class="input-container ic2">
                            <input type="text" id="contribution_sk" name="contribution_sk" class="input"
                                placeholder=" ">
                            <div class="cut"></div>
                            <label for="contribution_sk" class="placeholder">Contribution (SK)</label>
                        </div>
                    </div>
                </div>
                <div>
                    <?php if (isset($errmsgExist)) { ?>
                        
                            <?php echo $errmsgExist; ?>
                        
                    <?php } ?>
                    <button type="submit" class="submit">Add Person</button>
                </div>
            </div>
        </form>
    </main>
    <script>
        function showToast() {
            console.log("asd");
            event.preventDefault();
            swal({
                title: "Si si istý?",
                text: "Po kliknutí na ok vykonáš operáciu!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                customClass: {
                    confirmButton: 'btn-success',
                    cancelButton: 'btn-danger'
                },
                className: 'custom-modal'
            }).then((willDelete) => {
                if (willDelete) {

                    swal({
                        title: "Zmeny sa úspešne uložily!",
                        text: "Budeš automatický presmerovaný na hlavnú stránku!",
                        icon: "success",
                        buttons: false
                    });
                    setTimeout(function () {
                        document.querySelector('form').submit();
                    }, 2500);
                } else {
                    swal("Zrušil si zmenu!");
                    return false;
                }
            });
        }
    </script>
</body>

</html>