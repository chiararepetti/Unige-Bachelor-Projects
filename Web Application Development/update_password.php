<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en" class="theme-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifica password</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/site-icon.ico">
</head>
<body>
<?php
include 'navbar.php';
include 'scripts/update_password_script.php'
?>
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box is-shadowless p-6 has-background-black has-text-centered">
                    <?php
                    echo "<h3 class='title'> Attenzione " . $_SESSION["firstname"] . "!</h3>";
                    ?>
                    <p class="subtitle">Stai per <span class="has-text-danger">modificare</span> la tua password</p>
                    <p class="pb-4">Per modificare le tue informazioni personali premi <a class="has-text-link has-text-weight-bold" href="update_profile.php">qui</a></p>
                    <form action="update_password.php" method="post">
                        <div class="mt-4 p-3"><p class="title is-5">Vecchia password</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="password" placeholder="*****" name="pass" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Nuova password</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="password" placeholder="*****" name="new-pass" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Conferma nuova password</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="password" placeholder="*****" name="confirm" style="width: 70%">

                        <?php
                        if($error != ""){
                            echo '<div class="mt-4 notification is-danger is-dark" role="alert">'. $error .'</div>';
                        } else if ($success != ""){
                            echo '<div class="mt-4 notification is-success is-dark" role="alert">'. $success .'</div>';
                        }
                        ?>
                        <div class="mt-5"><button type="submit" name="submit" value="submit" class="py-3 button is-success">Conferma modifica</button></div>
                    </form>
                    <div class="mt-5"><a href="show_profile.php"><button class="py-3 button">Annulla modifiche</button></a>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<?php
include 'footer.php';
?>
</body>
</html>

