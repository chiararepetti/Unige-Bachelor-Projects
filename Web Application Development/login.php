<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if (isset($_SESSION['login'])){
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="it" class="theme-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/neonbutton.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.9/css/boxicons.min.css">
</head>
<body>
<?php
include 'navbar.php';
include 'scripts/login_check.php';
?>
<section>
    <div class="wrapper">
            <form action="login.php" method="post">
            <h1>Login</h1>
            <div class="input-box">
                <span>Indirizzo Email</span>
                <input type="email" placeholder="Username" name="email">
                <i class='bx bxs-user' style='color:#ffffff'></i>
            </div>
            <div class="input-box">
                <span>
                    Password
                </span>
                <input type="password" placeholder="******" name="pass">
                <i class='bx bxs-lock-alt' style='color:#ffffff'></i>
            </div>
            <!--<div class="remember-me">
                <input type="checkbox" name="rememberme">
                <span>Remember me?</span>
            </div>-->
            <?php
            if($error != ""){
                echo '<div class="notification is-danger is-light" role="alert">'. $error .'</div>';
            }
            ?>
            <div class="btn-login">
                <button type="submit" name="submit" value="submit" class="neon-button">
                    Accedi
                </button>
            </div>
            <div class="register-link">
                <p>Non hai un account? <a href="registration.php">Registrati</a></p>
            </div>
        </form>
    </div>
</section>
<?php
include 'footer.php';
?>
</body>
</html>