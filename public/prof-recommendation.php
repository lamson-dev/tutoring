<?php
include "templates/header.php"
?>

    <div class="row">
        <div class="large-4 columns">
            <label>Tutor GT-ID
                <input id="tut_gtid" type="text" placeholder="902910000"/>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>Descriptive Evaluation
                <textarea id="desc_eval" placeholder="Good tutor?"></textarea>
            </label>
        </div>
    </div>


    <div class="row">
        <div class="large-12 columns">
            <label>Rating</label>
            <input type="radio" name="rating" value="4" id="rate_high"><label for="rate_high">Highly Recommend</label>
            <input type="radio" name="rating" value="3" id="rate_medium"><label for="rate_medium">Recommend</label>
            <input type="radio" name="rating" value="2" id="rate_low"><label for="rate_low">Recommend with
                reservations</label>
            <input type="radio" name="rating" value="1" id="rate_no"><label for="rate_no">Do not recommend</label>
        </div>
    </div>

    <div class="row">
        <a href="main-menu.php" id="btn_submit_prof_eval" class="button small radius">Submit Evaluation</a>
    </div>


<?php
include "templates/footer.php"
?>
