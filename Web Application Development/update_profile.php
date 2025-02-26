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
    <title>Modifica profilo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/update_profile_script.php'
?>
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box is-shadowless p-6 has-background-black has-text-centered">
                    <?php
                    echo "<h3 class='title'> Attenzione " . $_SESSION["firstname"] . "!</h3>";
                    ?>
                    <p class="subtitle">Stai per <span class="has-text-danger has-text-weight-bold">modificare</span> le tue informazioni personali</p>
                    <p class="pb-4">Per modificare la password premi <a class="has-text-link has-text-weight-bold" href="update_password.php">qui</a></p>
                    <form action="update_profile.php" method="post">
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
                        if($error != ""){
                            echo '<div class="notification is-danger is-dark mt-3" role="alert">'.$error.'</div>';
                        }
                        ?>
                        <div class="mt-5"><button type="submit" name="submit" value="submit" class="py-3 button is-success">Conferma modifiche</button></div>
                    </form>
                    <div class="mt-5"><a href="show_profile.php"><button class="py-3 button">Annulla modifiche</button></a>
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

