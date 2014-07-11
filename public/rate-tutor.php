<?php
include "templates/header.php"
?>

    <div class="row">
        <div class="large-2 columns">
            <label>
                <select id="rate_school_list">
                    <option disabled>Department</option>
                    <!--                    <option value="CS">CS</option>-->
                    <!--                    <option value="ISyE">ISyE</option>-->
                    <!--                    <option value="ECE">ECE</option>-->
                </select>
            </label>

        </div>

        <div class="large-2 columns end">
            <label>
                <select id="rate_number_list">
                    <option disabled>Course Number</option>
                    <option value="4400">4400</option>
                    <option value="2110">2110</option>
                </select>
            </label>

        </div>

    </div>


    <div class="row">
        <div class="large-4 columns">
            <label>Tutor Name

                <input id="in_tutor_name" class="" type="text" placeholder="John Smith"/>
                <small id="in_tutor_name_error" class="error" style="display: none;">Invalid entry</small>
            </label>

        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>Descriptive Evaluation
                <textarea id="in_desc_eval" placeholder="Good tutor?"></textarea>
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
        <a href="main-menu.php" id="btn_submit_student_eval" class="button small radius">Submit Evaluation</a>
    </div>


<?php
include "templates/footer.php"
?>