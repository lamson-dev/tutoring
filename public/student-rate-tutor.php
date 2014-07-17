<?php
include "templates/header.php"
?>

<div id="student_rate_course" class="row">
    <?php include "templates/course-selector.html"; ?>
</div>


<div class="row">
    <div class="large-4 columns">
        <label>Tutor Name
            <!--                <input id="in_tutor_name" class="" type="text" placeholder="John Smith"/>-->
            <!--                <small id="in_tutor_name_error" class="error" style="display: none;">Invalid entry</small>-->
            <select id="rate_tutor_name_list">
                <!--                    <option selected disabled hidden value="">- Select Tutor Name -</option>-->
            </select>
        </label>

    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <label>Descriptive Evaluation
            <textarea id="rate_desc_eval" placeholder="Good tutor?"></textarea>
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
    <a href="#" id="btn_submit_student_eval" class="button small radius">Submit Evaluation</a>
</div>


<?php
include "templates/footer.php"
?>

<script>
    $(document).ready(function () {
        fetchCourseSchoolList("#student_rate_course");
    });
</script>
