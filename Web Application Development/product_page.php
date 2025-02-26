<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$_SESSION['last_visited'] = $_SERVER['HTTP_REFERER'];
?>
<!DOCTYPE html>
<html lang="it" class="theme-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prodotto</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mex_alert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/review_script.php';
require 'scripts/static_pages/product_page_script.php';
include 'scripts/cart/add_to_cart.php';
?>
<section class="section">
    <div class="container">
        <div class="columns p-6">
            <div class="column p-1">
                <div class="mb-4 px-6">
                    <?php echo '<img src="images/' . $cover . '.png" alt="album cover">' ?>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div>
                        <?php echo '<h1 class="mt-6 title is-1"> ' . $title . '</h1>' ?>
                        <?php echo '<p class="subtitle is-3 has-text-light-dark">' . $artist . '</p>' ?>
                        <br>
                        <?php echo '<p class="has-text-light subtitle is-6 mb-6"><b>Genere</b>: ' . $genre . '</p>' ?>
                        <?php
                            if($avgrating!= NULL) {
                                $avgrating = round($avgrating,1);
                            echo '<p class="has-text-light subtitle is-6 mb-6"> <b>Valutazione media</b>: ' . $avgrating . '<span class="icon has-text-warning"><i class="fas fa-star"></i></span></p>';
                        }
                        ?>
                        <p class="mb-8 is-inline-block">
                            <?php echo '<span class="is-size-3 has-text-light">' . $price . ' €</span>' ?>
                        </p>
                    </div>
                </div>
                <form id="add-to-cart-form" action="product_page.php" method="POST">
                    <input class="input mt-5" type="number" min="1" name="quantity" value="1" placeholder="1" style="width: 70px">
                    <?php
                        echo '<button name="add_to_cart" type="submit" value="'.$recordid.'" class="button mt-4 is-link is-fullwidth" style="width: 70%">Aggiungi al carrello</button>'; ?>
                    </form>
                    <div id="notification">Prodotto aggiunto al carrello!</div>
            </div>
        </div>
    </div>
</section>

<div class="section">
    <?php
        if (isset($_SESSION['login'])){
            include 'review.php';
        }
    ?>
</div>

<div class="section">
    <h1 class="title is-1">Il giudizio degli utenti &#128173;</h1>
    <h4 class="subtitle">Vediamo cosa i nostri utenti pensano di questo album!</h4>

    <div class="box is-shadowless">
        <?php
            if ($result2->num_rows==0) {
                echo "<h2 class='subtitle has-text-gray'> Non sono ancora presenti recensioni per questo articolo!</h2>";
            } else {
            echo '<table class="table">
            <thead>
            <tr>
            <th scope="col" class="big-screen" style="width: 200px">Utente</th>
            <th scope="col" class="big-screen" style="width: 200px">Rating</th>
            <th scope="col" class="big-screen" style="width: 200px">Commento</th></tr></thead><tbody>';
            while ($row2 = $result2->fetch_assoc()) {
            echo '<tr>';
            echo '<td class="big-screen">'.$row2['firstname'].'</td>';
            echo '<td class="big-screen">'.$row2['rating'].' <span class="icon has-text-warning"><i class="fas fa-star"></i></span></td>';
            echo '<td class="big-screen">'.$row2['opinion'].'</td>';
            if (isset($_SESSION['id']) && $row2['userid'] == $_SESSION['id']) {
                echo '<td>';
                echo '<form action="scripts/review_script.php" method="post">
                    <button class="button is-danger is-small mb-2" type="submit" name="delete_review" value="' .$row2['reviewid'].'">Elimina recensione</button>';
                echo '</form></td>';
            } else if (isset($_SESSION['login'])) {
                if (!($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'owner')) {
                    echo '<td>';
                    echo '<form action="scripts/review_script.php" method="post">
                    <button class="button is-danger is-small mb-2" type="submit" name="delete_review" value="' . $row2['reviewid'] . '">Elimina recensione</button>';
                    echo '</form></td>';
                }
            }
            echo '</tr>';
            }
            }
            ?>
            </tbody></table>
        </div>
    </div>
</body>
<script src="js/review.js"></script>
<script src="js/mex_alert_product.js"></script>
<?php
require 'footer.php';
?>
</body>
</html>