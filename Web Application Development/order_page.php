<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
?>
<?php
include 'scripts/orders/order_page_script.php';
?>
<!DOCTYPE html>
<html lang="it" class="theme-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Negozio di vinili spaziale</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mex_alert.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
require 'navbar.php';
?>
<section class="section" style="margin-bottom: 300px;">
    <div class="container" style="margin-top: 30px">
        <h1 class="title is-1">Ecco i tuoi ordini</h1>
        <p class="subtitle">Visualizza i tuoi ordini</p>
        <?php
        if (isset($_GET['order']) && $_GET['order'] === 'added') {
            echo "<div id='notification' class='show'>Ordine inviato!</div>";
        }
        ?>
        <div class="box is-shadowless">
            <?php
                if (empty($orders)) {
                    echo "<p class='message'>Non hai ancora effettuato alcun ordine.</p>";
                } else {
                    echo '<table class="table" style="margin:auto">
                        <thead>
                        <tr>
                        <th scope="col" class="big-screen" style="width: 200px">Ordine</th>
                        <th scope="col" class="big-screen" style="width: 200px">Totale</th>
                        <th scope="col" class="big-screen" style="width: 200px">Effettuato il</th>
                        <th scope="col" class="big-screen" style="width: 200px">Stato</th>
                        </tr></thead><tbody>';
                        foreach ($orders as $order) {
                            echo '<tr>';
                            echo '<td class="big-screen">' .$order['orderid'].'</td>';
                            echo '<td class="big-screen">' .$order['total'].'</td>';
                            echo '<td class="big-screen">' .$order['rdate'].'</td>';
                            if ($order['state'] == 'IN PREPARAZIONE') {
                                echo '<td class="big-screen">' .$order['state'].'</td>';
                            } else {
                                echo '<td class="big-screen">' .$order['state'].' in data: '. $order['ddate'] .'</td>';
                            }
                            echo '<td>';
                            echo '<form action="order_page_details.php" method="post"><button class="button is-small mb-2" type="submit" name="details" value="'.$order['orderid'].'">Vedi dettagli</button></form>';
                            echo '</td></tr>';
                        }
                        echo '</tbody></table>';
                    }
                ?>
        </div>
        <br>
        <p> Se hai degli ordini in preparazione che vuoi modificare, contattaci <a href='contacts.php'>qui</a>. </p>
    </div>
</section>
<?php
include 'footer.php'
?>
</body>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const notification = document.getElementById('notification');
        if (notification) {
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000); 
        }
    });
</script>
</html>
