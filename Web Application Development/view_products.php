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
    <title>&#9734; Catalogo &#9734;</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/site-icon.ico">
</head>
<body class="has-navbar-fixed-top">
<?php
include 'navbar.php';
include 'scripts/static_pages/view_products_script.php';
?>
<section class="section" style="margin-bottom: 300px">
    <div class="container">
        <h1 class="title is-1 is-spaced">Il nostro catalogo</h1>
        <?php
        if ($result->num_rows == 0) {
            echo "<div class='title has-text-danger is-4'>La ricerca non ha prodotto risultati!</div>";
        } else if (!empty($search)){
            echo '<div class="title is-4 has-text-weight-bold has-text-success">Risultati per la ricerca: '.$search.'</div>';
        }
        ?>
        <div class="dropdown" style="margin-top: 10px; margin-bottom: 25px">
            <div class="dropdown-trigger">
                <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">
                    <span>Filtra risultato</span>
                </button>
            </div>
            <div class="dropdown-menu" id="dropdown-menu" role="menu">
                <div class="dropdown-content">
                    <?php echo "<a href='view_products.php?filter=released DESC&search=" . $search . "' class='dropdown-item'>";?>
                        Dal più recente
                    </a>
                    <?php echo "<a href='view_products.php?filter=released ASC&search=" . $search . "' class='dropdown-item'>";?>
                        Dal meno recente
                    </a>
                    <?php echo "<a href='view_products.php?filter=price DESC&search=" . $search . "' class='dropdown-item'>";?>
                        Dal più costoso
                    </a>
                    <?php echo "<a href='view_products.php?filter=price ASC&search=" . $search . "' class='dropdown-item'>";?>
                        Dal meno costoso
                    </a>
                    <?php echo "<a href='view_products.php?filter=title ASC&search=" . $search . "' class='dropdown-item'>";?>
                        In ordine alfabetico A-Z
                    </a>
                    <?php echo "<a href='view_products.php?filter=title DESC&search=" . $search . "' class='dropdown-item'>";?>
                        In ordine alfabetico Z-A
                    </a>
                </div>
            </div>
        </div>
        <div class="fixed-grid has-1-cols-mobile has-4-cols-tablet has-4-cols-desktop has-4-cols-widescreen has-4-cols-fullhd">
            <div class="grid">
                <?php
                while ($row = $result->fetch_assoc()){
                    $recordid= $row['recordid'];
                    $avgrating = $row['avgrating'];
                    echo "<div class='cell p-4'><div class='card is-shadowless'><div class='card-image'><figure class='image'>";
                    echo "<a href='product_page.php?recordid=".$recordid."' class='card-footer-item'>";
                    echo "<img src='images/" . $row['cover'] . ".png'></a>";
                    echo "</figure></div><div class='card-content'><div class='media'><div class='media-content'>";
                    echo "<p class='title is-4'>" . $row["title"] . "</p>";
                    echo "<p class='subtitle is-5'>" . $row['artist'] . "</p>";
                    echo "<p class='subtitle is-6 has-text-light-dark'>" . $row['price'] . " €</p>";
                    if ($avgrating !== null) {
                        $fullStars = floor($avgrating);
                        $halfStar = $avgrating - $fullStars >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                    
                        echo "<p class='subtitle is-6 has-text-light-dark'>Rating: ";
                        for ($i = 0; $i < $fullStars; $i++) {
                            echo "<span class='icon has-text-warning'><i class='fas fa-star'></i></span>";
                        }
                        for ($i = 0; $i < $halfStar; $i++) {
                            echo "<span class='icon has-text-warning'><i class='fas fa-star-half-alt'></i></span>";
                        }
                        for ($i = 0; $i < $emptyStars; $i++) {
                            echo "<span class='icon has-text-light'><i class='fas fa-star'></i></span>";
                        }
                        echo "</p>";
                    } else {
                        echo "<p class='subtitle is-6 has-text-light-dark'>Non ci sono recensioni</p>";
                    }
                    echo "</div></div></div><footer class='card-footer'>";
                    echo "</div></div>";
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php
require 'footer.php';
?>
<script src="js/view_products.js"></script>
</body>
</html>