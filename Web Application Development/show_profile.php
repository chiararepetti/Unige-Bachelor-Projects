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
<html lang="it" class="theme-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>&#9734; Il tuo profilo &#9734;</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
?>
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box is-shadowless p-6 has-background-black has-text-centered">
                    <?php
                    echo "<h3 class='title'> Ciao " . $_SESSION["firstname"] . "! &#128075;</h3>";
                    ?>
                    <p class="subtitle">Ecco le tue informazioni personali</p>
                    <div class="p-3"><p class="title is-5">Nome</p><p class="subtitle"><?php echo $_SESSION['firstname']?></p></div>
                    <div class="p-3"><p class="title is-5">Cognome</p><p class="subtitle"><?php echo $_SESSION['lastname']?></p></div>
                    <div class="p-3"><p class="title is-5">Email</p><p class="subtitle"><?php echo $_SESSION['email']?></p></div>
                    <div class="pb-5 pt-3"><p class="title is-5">Pianeta di provenienza</p><p class="subtitle"><?php echo ucfirst($_SESSION['planet'])?></p></div>
                    <a href="order_page.php"><button class="mt-3 has-text-white button is-link is-fullwidth p-3">Visualizza i tuoi ordini</button></a>
                    <a href="update_profile.php"><button class="mt-3 button is-fullwidth p-3">Modifica profilo</button></a>
                    <a href="update_password.php"><button class="mt-3 button is-fullwidth p-3 has-text-danger">Modifica password</button></a>
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

