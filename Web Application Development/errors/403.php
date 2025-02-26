<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
?>
<!doctype html>
<html lang="it" class="theme-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Negozio di vinili spaziale</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/x-icon" href="../images/site-icon.ico">
</head>
<section class="section" style="margin-bottom: 400px">
    <div class="container has-text-centered p-6">
        <h1 class="title is-1 is-spaced">Errore 403! Fermo, tu non puoi entrare qui!</h1>
        <p class="subtitle">
            Non hai i permessi per accedere a questa pagina. Premi <a href="../index.php">qui</a> per tornare alla home.
        </p>
    </div>
</section>
<?php
require '../footer.php';
?>
</body>
</html>