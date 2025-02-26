<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>
<!DOCTYPE html>
<html lang="it" class="theme-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrazione</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/neonbutton.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/registration_check.php';
?>

<section>
    <div class="form-wrapper">
        <form action="registration.php" method="post">
            <h1>Crea un nuovo account</h1>
            <p>I campi contrassegnati da * sono obbligatori.</p>
            <div class="input-box">
                <span>Nome*</span>
                <input type="text" placeholder="Monica" name="firstname">
            </div>
            <div class="input-box">
                <span>Cognome*</span>
                <input type="text" placeholder="Bellucci" name="lastname">
            </div>
            <div class="input-box">
                <span>Email*</span>
                <input type="email" placeholder="monicabellucci@domain.com" name="email">
            </div>
            <div class="input-box">
                <span>Password*</span>
                <input type="password" placeholder="******" name="pass">
            </div>
            <div class="input-box">
                <span>Conferma password*</span>
                <input type="password" placeholder="******" name="confirm">
            </div>
            
            <div class="input-box">
                <span>Pianeta di provenienza</span>
                <div class="my-select">
                    <select name="planet">
                        <option value="">Seleziona un pianeta</option>
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
            </div>

            <div class="condition-terms">
                <input type="checkbox" name="terms" value="1" class="checkbox-style">
                <label>
                    Accetto i&#160;<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="condition-link"> termini e condizioni</a>.
                </label>
            </div>

            <?php 
            if($error != ""){
                echo '<div class="notification is-danger is-light" role="alert">'. $error .'</div>';
            }
            ?>
            <div class="btn-login">
                <button type="submit" name="submit" value="submit" class="neon-button">Registrami!</button>
            </div>
        </form>
    </div>
</section>
<?php
include 'footer.php';
?>
</body>
</html>
