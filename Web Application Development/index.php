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
    <title>AD ASTRA ★ Negozio di vinili spaziale</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
require 'navbar.php';
include 'scripts/static_pages/view_products_script.php';
?>
<?php
    if(isset($_GET['logout'])) {
        echo '<article class="message is-success logout-message" id="logout-message">
                <div class="message-header">
                <p>Hai fatto logout!</p>
                </div>
             </article>';
        echo '<script type="text/javascript">
                setTimeout(function(){
                    document.getElementById("logout-message").style.display = "none";
                }, 3000);
              </script>';
    }
?>
<!-- Presentazione del sito -->
<section class="section">
    <div class="container has-text-centered p-6">
        <h1 class="title is-1 is-spaced">&#10024;AD ASTRA RECORD STORE&#10024;</h1>
        <p class="subtitle has-text-weight-bold">
            Negozio di vinili intergalattico.
        </p>
        <p class="title is-4 pt-6">
            Esplora il catalogo e trova il tuo prossimo artista preferito! 
        </p>
    </div>
</section>
<!-- Servizi speciali -->
<section class="section has-background-black">
    <div class="container">
        <h2 class="title is-1">Perché acquistare da noi?</h2>
        <p class="subtitle mb-6">Ecco alcuni motivi per cui dovresti scegliere di acquistare la tua musica preferita da noi.</p>
        <div class="columns">
            <div class="column is-one-third" style="margin-top: 30px">
            <p class="title is-4">Catalogo sempre aggiornato &#128640;</p>
                <p class="subtitle">All'interno del nostro catalogo potrai sempre trovare le ultime uscite provenienti 
                    da tutta la galassia grazie ai nostri sistemi di aggiornamento quantistico.</p>
            </div>
            <div class="column is-one-third" style="margin-top: 30px; margin-right: 30px; margin-left:30px">
                <p class="title is-4">Spedizione velocissima &#128230;</p>
                <p class="subtitle">Grazie ai nostri veicoli di spedizione che viaggiano alla velocità della luce,
                    le tue spedizioni arriveranno da te in pochissimo tempo.</p>
                </p>
            </div>
            <div class="column is-one-third" style="margin-top: 30px">
                <p class="title is-4">Non siamo alieni cattivi &#128125;</p>
                <p class="subtitle">A differenza dei nostri competitor, noi non vogliamo assolutamente
                    conquistare il pianeta Terra e sterminare la razza umana. Vogliamo solo vendervi dei vinili!</p>
                </p>
            </div>
        </div>
    </div>
</section>
<!-- Le novità -->
<section class="section">
    <div class="container">
        <h2 class="title is-1">Le novità &#127911;</h2>
        <p class="subtitle is-spaced pb-5">Non perderti le ultime uscite!</p>
        <div class="grid is-col-min-12 is-gap-8">
            <?php
            $counter = 0;
            while (($row = $result->fetch_assoc()) && ($counter<8)){
                echo "<div class='cell p-4'><div class='card is-shadowless'><div class='card-image'><figure class='image'>";
                echo "<a href='product_page.php?recordid=". $row["recordid"] ."'><img src='images/" . $row['cover'] . ".png'></a>";
                echo "</figure></div><div class='card-content'><div class='media'><div class='media-content'>";
                echo "<p class='title is-4'>" . $row["title"] . "</p>";
                echo "<p class='subtitle is-5'>" . $row['artist'] . "</p>";
                echo "</div></div></div></div></div>";
                $counter+=1;
            }
            ?>
        </div>
    </div>
</section>
<!-- About us -->
<section class="section">
    <div class="container">
        <h2 class="title is-2">Dove trovarci &#128760;</h2>
        <p class="subtitle">
            Spediamo in tutta la galassia ma, se vuoi venire a trovarci, siamo situati sul pianeta Marte, in un punto strategico facilmente raggiungibile da qualsiasi
            altro pianeta grazie ai nostri sistemi di teletrasporto quantistico.
            <br>
            <br>
            <b>Indirizzo</b>: Da qualche parte su Marte.
        </p>
    </div>
</section>
<?php
require 'footer.php';
?>
</body>
</html>