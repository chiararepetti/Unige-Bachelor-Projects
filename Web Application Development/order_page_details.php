<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="it" class="theme-dark">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Negozio di vinili spaziale</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/orders/order_page_details_script.php';
?>
<section class="section">
    <div class="container"> 
        <h1 class="title is-1">I vinili che hai acquistato</h1>
        <h4 class="title is-4 has-text-grey">Numero ordine: <?php echo $_POST["details"];?></h4>

        <div class="fixed-grid has-1-cols-mobile has-4-cols-tablet has-4-cols-desktop has-4-cols-widescreen has-4-cols-fullhd">
            <div class="grid">
            <?php
                if (empty($orders)) {
                    echo "<p class='message'>Non hai ancora effettuato alcun ordine.</p>";
                } else {
                    foreach ($orders as $order) {
                        $oid = $order["orderid"];
                        $recordid = $order['recordid'];
                        $title = $order['title'];
                        $artist = $order['artist'];
                        $price = $order['price'];
                        $cover = $order['cover'];
                        $quantity = $order['quantity'];
                        echo "<div class='cell p-5'><div class='card is-shadowless'><div class='card-image'><figure class='image'>";
                        echo "<img src='images/".$cover.".png'>";
                        echo "</figure></div><div class='card-content'><div class='media'><div class='media-content'>";
                        echo "<p class='title is-4'>".$title."</p>";
                        echo "<p class='subtitle is-5'>".$artist."</p>";
                        echo "<p class='subtitle is-6 has-text-light-dark'>".$price." €</p>";
                        echo "<p class='subtitle is-6 has-text-light-dark'>Copie: ".$quantity."</p>";
                        include 'scripts/orders/order_bought_check.php';
                        if ($state == "CONSEGNATO") {
                            echo "<a href='product_page.php?recordid=".$recordid."' class='button is-success'> Scrivi una recensione </a>";
                        }
                        echo "</div></div></div><footer class='card-footer'>";
                        echo "</div></div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
</body>
</html>