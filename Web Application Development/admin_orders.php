<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'owner'){
    header("Location: errors/403.html");
}
?>
<!DOCTYPE html>
<html lang="it" class="theme-light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin ordini</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mex_alert.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/admin/get_all_orders.php';
require 'scripts/admin/admin_delete_order_script.php';
require 'scripts/admin/admin_ship_order_script.php';
?>
<section class="section has-background-light">
    <div class="container" style="margin-bottom: 300px; margin-top:30px">
        <div id="notification" class="notification"></div>
        <form action="admin_orders.php" method="post" onsubmit="setLocalStorage('allShipped')">
            <button class="button is-link is-big mb-2" type="submit" name="ship_all">Spedisci tutti</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="big-screen" style="width: 200px">ID</th>
                    <th scope="col" class="big-screen" style="width: 200px">Utente</th>
                    <th scope="col" class="big-screen" style="width: 200px">Totale</th>
                    <th scope="col" class="big-screen" style="width: 200px">Ricevuto il</th>
                    <th scope="col" class="big-screen" style="width: 200px">Spedito il</th>
                    <th scope="col" class="big-screen" style="width: 200px">Consegnato il</th>
                    <th scope="col" class="big-screen" style="width: 200px">Stato</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($result->num_rows == 0){
                echo '<h2 class="title has-text-danger">Non sono presenti ordini nel sito al momento</h2>';
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td class="big-screen">' . $row['orderid'] . '</td>';
                    echo '<td class="big-screen">' . $row['userid'] . '</td>';
                    echo '<td class="big-screen">' . $row['total'] . '</td>';
                    echo '<td class="big-screen">' . $row['rdate'] . '</td>';
                    echo '<td class="big-screen">' . $row['sdate'] . '</td>';
                    echo '<td class="big-screen">' . $row['ddate'] . '</td>';
                    echo '<td class="big-screen">' . $row['state'] . '</td>';
                    echo '<td>';
                    if ($row['state'] == 'CONSEGNATO') {
                        echo '<button class="button is-success has-text-white is-small mb-2">OK</button>';
                    } else {
                        echo '<form action="admin_order_details.php" method="post">
                                <button class="button is-link is-small mb-2" type="submit" name="view_details" value="' . $row['orderid'] . '">Visualizza dettagli</button>
                              </form>
                            <form action="admin_orders.php" method="post">
                                  <button class="button is-link is-small mb-2" type="submit" name="ship_order" value="' . $row['orderid'] . '" onclick="setLocalStorage(\'orderShipped\')">Spedisci</button>
                                  <button class="button is-danger is-small mb-2" type="submit" name="delete_order" value="' . $row['orderid'] .  '" onclick="setLocalStorage(\'orderDeleted\')">Elimina ordine</button>
                              </form>';
                    }
                    echo '</td></tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</section>
<script src="js/mex_alert_admin.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
