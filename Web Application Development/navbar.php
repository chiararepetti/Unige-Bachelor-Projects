<nav class="navbar is-fixed-top">
    <!-- Logo del sito -->
    <div class="navbar-brand">
        <a class="navbar-item" href="index.php">
            <img src="images/site-icon.png" alt="site logo" style="max-height: 60px" class="py-2 px-2">
        </a>
        <!-- Burger che si attiva sugli schermi piccoli -->
        <a id="nav-burger" class="navbar-burger">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>
    <div class="navbar-menu" id="nav-links">
        <!-- L'inizio della navbar è statico (i tasti non cambiano) -->
        <div class="navbar-start">
            <a class="navbar-item has-text-weight-bold" href="view_products.php">Catalogo</a>
            <div class="navbar-item has-dropdown is-hoverable" id="nav-genere">
                <a class="navbar-link has-text-weight-bold">Genere</a>
                    <div class="navbar-dropdown">
                        <a href='view_products.php?search=pop' class="navbar-item">Pop</a>
                        <a href='view_products.php?search=country' class="navbar-item">Country</a>
                        <a href='view_products.php?search=rock' class="navbar-item">Rock</a>
                        <a href='view_products.php?search=hip-hop' class="navbar-item">Hip-Hop</a>
                        <a href='view_products.php?search=kpop' class="navbar-item">K-Pop</a>
                        <hr class="navbar-divider">
                        <a href="view_products.php" class="navbar-item">Sfoglia l'intero catalogo</a>
                    </div>
            </div>
            <a class="navbar-item has-text-weight-bold" href="contacts.php">Contatti</a>
            <div class="navbar-item" style="margin-left: 50px">
                <form action="view_products.php" method="GET">
                    <input class="input" type="text" name="search" placeholder="Cerca il tuo prossimo album preferito..." style="width: 400px">
                    <button class="button is-link" type="submit">Cerca</button>
                </form>
            </div>
        </div>
        <!-- La seconda parte della navbar cambia se l'utente è loggato (e cambia ancora se è un admin)-->
        <div class="navbar-end">
            <?php if (isset($_SESSION["login"])) {
                if ($_SESSION['role']=='admin' || $_SESSION['role']=='owner'){
                    echo '<a class="navbar-item has-text-weight-bold has-text-link" href="admin_dashboard.php">Area amministrativa</a>';
                    echo '<a class="navbar-item has-text-weight-bold" href="show_profile.php">Profilo</a>';
                    echo '<a class="navbar-item has-text-weight-bold has-text-danger" href="logout.php">Logout</a>';
                } else {
                    echo '<a class="navbar-item has-text-weight-bold" href="show_profile.php">Profilo</a>';
                    echo '<a class="navbar-item has-text-weight-bold" href="cart.php">Carrello</a>';
                    echo '<a class="navbar-item has-text-weight-bold has-text-danger" href="logout.php">Logout</a>';
                }
            } else {
                echo '<a class="navbar-item has-text-weight-bold has-text-link" href="login.php">Login</a>';
                echo '<a class="navbar-item has-text-weight-bold has-text-danger" href="registration.php">Sign-up</a></div>';
            }
            ?>
        </div>
    </div>
</nav>
