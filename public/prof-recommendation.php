<?php
include "templates/header.php"
?>

    <div class="row">
        <div class="large-4 columns">
            <label>Tutor GT-ID
                <input id="rec_tutor_gtid" type="text" placeholder="902910000" maxlength="9"/>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>Descriptive Evaluation
                <textarea id="rec_desc_eval" placeholder="Good tutor?"></textarea>
            </label>
        </div>
    </div>


    <div class="row">
        <div class="large-12 columns">
            <label>Rating</label>
            <input type="radio" name="rec_rating" value="4" id="rec_high"><label for="rec_high">Highly Recommend</label>
            <input type="radio" name="rec_rating" value="3" id="rec_medium"><label for="rec_medium">Recommend</label>
            <input type="radio" name="rec_rating" value="2" id="rec_low"><label for="rec_low">Recommend with
                reservations</label>
            <input type="radio" name="rec_rating" value="1" id="rec_no"><label for="rec_no">Do not recommend</label>
        </div>
    </div>

    <div class="row">
        <a href="#" id="btn_submit_prof_eval" class="button small radius">Submit Evaluation</a>
    </div>


<?php
include "templates/footer.php"
?>
