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
include 'scripts/admin/get_all_users.php';
require 'scripts/admin/admin_update_profile_script.php';
require 'scripts/admin/admin_delete_user_script.php';
?>
<section class="section has-background-light">
    <div class="container" style="margin-bottom: 300px;">
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="big-screen"style="width: 200px">ID</th>
                <th scope="col" class="big-screen"style="width: 200px">Nome</th>
                <th scope="col" class="big-screen"style="width: 200px">Cognome</th>
                <th scope="col"style="width: 200px">Email</th>
                <th scope="col"style="width: 200px">Planet</th>
                <th scope="col" class="big-screen"style="width: 200px">Tipo Utente</th>
                <th scope="col" class="right" style="width: 600px">Azioni</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row=$result->fetch_assoc()){
                echo '<td class="big-screen">' .$row['userid'].'</td>';
                echo '<td class="big-screen">' .$row['firstname'].'</td>';
                echo '<td class="big-screen">' .$row['lastname'].'</td>';
                echo '<td class="big-screen">' .$row['email'].'</td>';
                echo '<td class="big-screen">' .$row['planet'].'</td>';
                echo '<td class="big-screen">' .$row['role'].'</td>';
                echo '<td>';
                echo '<form action="admin_update_profile.php" method="post"><button class="button is-small mb-2" type="submit" name="alter_info" value="'.$row['userid'].'">Modify info</button></form>';
                echo '<form action="admin_users.php" method="post"><button class="button is-small mb-2 p is-danger" type="submit" name="delete_user" value="'.$row['userid'].'">DELETE user</button></form>';
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
