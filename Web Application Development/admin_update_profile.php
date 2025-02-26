<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin'&& $_SESSION['role']!='owner'){
    header("Location: errors/403.php");
}
?>
<!DOCTYPE html>
<html lang="it" class="theme-light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifica Profilo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/admin/admin_update_profile_script.php'
?>
<section class="section has-background-light">
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box p-6 has-background-white has-text-centered">
                    <?php
                    echo "<h3 class='title is-4 is-spaced'>" . $_SESSION["firstname"] . ", stai modificando il profilo di userid=".$_POST['alter_info']."</h3>";
                    ?>
                    <form action="admin_update_profile.php" method="post">
                        <div class="mt-4 p-3"><p class="title is-5">Nome</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="text" placeholder="Monica" name="firstname" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Cognome</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="text" placeholder="Bellucci" name="lastname" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Email</p><p class="subtitle"></p></div>
                        <input class="input py-5" type="email" placeholder="monicabellucci@domain.com" name="email" style="width: 70%">

                        <div class="mt-4 p-3"><p class="title is-5">Pianeta di provenienza</p><p class="subtitle"></p></div>
                        <div class="select is-rounded">
                            <select name="planet">
                                <option value="mercurio">Mercurio</option>
                                <option value="venere">Venere</option>
                                <option value="terra">Terra</option>
                                <option value="luna">La Luna</option>
                                <option value="marte">Marte</option>
                                <option value="giove">Giove</option>
                                <option value="saturno">Saturno</option>
                                <option value="urano">Urano</option>
                                <option value="nettuno">Nettuno</option>
                                <option value="plutone">Plutone</option>
                            </select>
                        </div>
                        <?php
                        if($_SESSION['role']=='owner'){
                            echo'<div class="mt-4 p-3"><p class="title is-5">Ruolo</p><p class="subtitle"></p></div>
                                <div class="select is-rounded">
                                <select name="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                </select></div>';
                        }
                        ?>
                        <?php echo '<div class="mt-5"><button type="submit" name="submit" value="'.$_POST['alter_info'].'" class="py-3 button is-success">Conferma modifiche</button></div>';?>
                    </form>
                    <div class="mt-5"><a href="admin_users.php"><button class="py-3 button">Annulla modifiche</button></a>
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

