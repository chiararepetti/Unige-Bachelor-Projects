<h1 class="mt-6 title is-1">Lascia la tua recensione <?php echo $_SESSION["firstname"];?>! &#9997;</h1>
<h4 class="subtitle">Il tuo parere è importante!</h4>
    <form action="scripts/review_script.php" method="post">
        <div class="rating">
            <input type="number" name="rating" hidden="hidden">
            <div class="star">
                <i class='fa fa-star' style="--i: 0;"></i>
            </div>
            <div class="star">
                <i class='fa fa-star' style="--i: 1;"></i>
            </div>
            <div class="star">
                <i class='fa fa-star' style="--i: 2;"></i>
            </div>
            <div class="star">
                <i class='fa fa-star' style="--i: 3;"></i>
            </div>
            <div class="star">
                <i class='fa fa-star' style="--i: 4;"></i>
            </div>
        </div>
        <textarea style="margin-top: 30px; margin-bottom: 30px" class="textarea" name="opinion" maxlength="400" rows="5" placeholder="Scrivi la tua opinione qui..."></textarea>
        <div class="buttons">
            <?php
                echo '<button class="button is-link is-responsive" type="submit" name="review" value = "'.$recordid.'">Invia recensione</button>'
            ?>
        </div>
    </form>