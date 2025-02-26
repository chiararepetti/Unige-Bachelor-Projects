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
    <title>Admin dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/admin/get_all_products.php';
require 'scripts/admin/admin_update_product_script.php';
require 'scripts/admin/admin_delete_product_script.php';
require 'scripts/admin/admin_add_product_script.php';
?>
<section class="section has-background-light">
    <div class="container mb-5">
    <div class="container">
        <form action="admin_add_product.php" method="post">
            <button class="button is-large mb-5 is-success" type="submit" name="add_product" value="">Add new product</button>
        </form>
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="big-screen"style="width: 200px">ID</th>
                <th scope="col" class="big-screen"style="width: 200px">Titolo</th>
                <th scope="col" class="big-screen"style="width: 200px">Artista</th>
                <th scope="col"style="width: 200px">Data rilascio</th>
                <th scope="col"style="width: 200px">Genere</th>
                <th scope="col" class="big-screen"style="width: 200px">Prezzo</th>
                <th scope="col" class="big-screen"style="width: 200px">Cover path</th>
                <th scope="col" class="right" style="width: 600px">Azioni</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row=$result->fetch_assoc()){
                echo '<td class="big-screen">' .$row['recordid'].'</td>';
                echo '<td class="big-screen">' .$row['title'].'</td>';
                echo '<td class="big-screen">' .$row['artist'].'</td>';
                echo '<td class="big-screen">' .$row['released'].'</td>';
                echo '<td class="big-screen">' .$row['genre'].'</td>';
                echo '<td class="big-screen">' .$row['price'].'</td>';
                echo '<td class="big-screen">' .$row['cover'].'.png</td>';
                echo '<td>';
                echo '<form action="admin_update_product.php" method="post"><button class="button is-small mb-2" type="submit" name="alter_product" value="'.$row['recordid'].'" onclick="setLocalStorage(\'modifiedOrder\')">Modify info</button></form>';
                echo '<form action="admin_products.php" method="post"><button class="button is-small mb-2 p is-danger" type="submit" name="delete_product" value="'.$row['recordid'].'" onclick="setLocalStorage(\'productDeleted\')" >DELETE product</button></form>';
                echo '</td></tr>';
            }?>
            </tbody>
        </table>
    </div>
</section>
<?php
include 'footer.php';
?>
</body>
</html>