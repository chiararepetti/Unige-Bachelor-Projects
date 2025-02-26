<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin' && $_SESSION['role']!='owner'){
    header("Location: errors/403.php");
}
?>
<!DOCTYPE html>
<html lang="it" class="theme-light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>modifica profilo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/admin/admin_update_product_script.php'
?>
<section class="section has-background-light">
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box p-6 has-background-white has-text-centered">
                    <?php
                    echo "<h3 class='title is-4 is-spaced'>" . $_SESSION["firstname"] . ", stai modificando il prodotto recordid=".$_POST['alter_product']."</h3>";
                    ?>
                    <form action="admin_update_product.php" method="post">
                        <div class="mt-4 p-3"><p class="title is-5">Titolo</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="text" placeholder="Titolo" name="title" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Artista</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="text" placeholder="Artista" name="artist" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Data di rilascio</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="date" placeholder="YYYY-MM-DD" name="released" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Genere</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="text" placeholder="Genere" name="genre" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Prezzo</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="text" placeholder="00.00" name="price" style="width: 70%">

                        <?php echo '<div class="mt-5"><button type="submit" name="submit" value="'.$_POST['alter_product'].'" class="py-3 button is-success">Conferma modifiche</button></div>';?>
                    </form>
                    <div class="mt-5"><a href="admin_products.php"><button class="py-3 button">Annulla modifiche</button></a>
                    </div>
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

