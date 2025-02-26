<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])){
    header("Location: login.php");
} else if ($_SESSION['role']!='admin'&& $_SESSION['role']!='owner') {
    header("Location: errors/403.php");
}
?>
<!DOCTYPE html>
<html lang="it" class="theme-light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
?>
<section class="section has-background-light">
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box p-6 has-background-white has-text-centered">
                    <?php
                    echo "<h3 class='title'> Dashboard di " . $_SESSION["firstname"] . "</h3>";
                    ?>
                    <p class="subtitle">Amministra il sito</p>
                    <a href="admin_products.php"><button class="mt-3 button is-fullwidth p-3">Gestione prodotti</button></a>
                    <a href="admin_users.php"><button class="mt-3 button is-fullwidth p-3">Gestione utenti</button></a>
                    <a href="admin_orders.php"><button class="mt-3 button is-fullwidth p-3">Gestione ordini</button></a>
                    <a href="#"><button class="mt-3 button is-fullwidth p-3 is-danger">Distruggi la Via Lattea</button></a>
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

