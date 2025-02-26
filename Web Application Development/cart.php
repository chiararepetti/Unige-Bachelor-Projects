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
    <title>Carrello</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
require 'scripts/cart/cart_script.php';
include 'scripts/cart/remove_from_cart.php';
require 'scripts/cart/checkout_script.php';
?>
<section class="section" style="margin-bottom: 100px;">
    <div class="container">
        <h2 class="title is-1">Il tuo carrello &#128722;</h2>
        <?php
            if($result->num_rows==0){
                echo '<article class="message is-danger">
                    <div class="message-body">
                        <strong>Il tuo carrello è vuoto!</strong>
                    </div></article>';
            } else {
                echo '<form action="cart.php" method="POST">';
                echo '<button type="submit" name="flush_cart" value=' .$_SESSION['cart'].' class="button mt-5 mb-5 is-danger is-fullwidth" style="width: 29%">Svuota carrello</button></form>';
                echo '<box class="box is-shadowless">';
                echo '<div class="column is-12 is-8-widescreen mb-8 mb-0-widescreen">
                    <div class="is-hidden-touch columns">
                        <div class="column is-5">
                            <h4 class="has-text-light">&#160;&#160;&#160;Prodotto</h4>
                        </div>
                        <div class="column is-5">
                            <h4 class="has-text-light">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Quantità</h4>
                        </div>
                        <div class="column is-5">
                            <h4 class="has-text-light">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Totale</h4>
                        </div>
                    </div>
                    </div>';
                $total_price = 0;
                while ($row = $result->fetch_assoc()) {
                    $total_price += floatval($row['quantity'] * $row['price']);
                    echo '<div class="columns">
                        <div class="column is-4 mb-3">
                        <div class="is-flex is-justify-content-center is-align-items-center" style="width: 100px; height: 120px;">
                            <img class="image is-fullwidth" style="object-fit: contain;" src="images/' . $row['cover'] . '.png" alt="">
                        </div>
                        <h3 class="title is-6 has-text-weight-bold has-text-white">' .$row['title']. '</h3>
                        <h6 class="subtitle is-6">' . $row['artist'] . '</h6>
                        </div>
                   
                    <div class="column is-3">
                        <h6 class="subtitle mt-5">' . $row['quantity'] . '</h6>
                    </div>
                    <div class="column is-3">
                        <p class="subtitle mt-5">' . floatval($row['quantity'] * $row['price']) . ' €</p>
                    </div>
                    <div class="column is-3">
                    <form action="cart.php" method="POST">
                        <button type="submit" name="remove_one" value=' . $row['recordid'] . ' class="button mt-4 is-danger is-fullwidth" style="width: 50%">Rimuovi uno</button>
                        <button type="submit" name="remove_all" value=' . $row['recordid'] . ' class="button mt-4 is-danger is-fullwidth" style="width: 50%">Rimuovi tutti</button>
                    </form>
                    </div>
                    </div>';
                    $query = "UPDATE carts SET total = ? WHERE cartid = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("di", $total_price, $_SESSION['cart']);
                    $stmt->execute();
                }
                echo '</box>';
                if ($result->num_rows == 0) {
                    $total_price = 0;
                } else {
                    echo '<div class="box is-shadowless" style="margin-top: 50px;">
                                <h3 class="title is-2 m-5 has-text-white">Totale: ' . $total_price . '€</h3>';
                    echo '<form action="checkout.php" method="POST">
                    <button type="submit" name="checkout" value=' . $total_price . ' class="button m-5 is-success" style="width: 50%">Vai al checkout</button>
                    </form>';
                }
            }
            ?>
    </div>
</section>
<?php
include 'footer.php';
?>
</body>
</html>
