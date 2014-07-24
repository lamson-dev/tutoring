<?php
include "templates/header.php"
?>

<fieldset class="row">
    <legend>Student Information</legend>

    <div class="row">
        <div class="large-4 columns end">
            <label>GaTech ID
                <input id="in_app_gtid" type="text" placeholder="902333001" maxlength="9"/>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-4 columns">
            <label>First Name
                <input id="in_app_fname" type="text" placeholder="John" maxlength="50"/>
            </label>
        </div>

        <div class="large-4 columns end">
            <label>Last Name
                <input id="in_app_lname" type="text" placeholder="Smith" maxlength="50"/>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-4 columns">
            <label>Email
                <input id="in_app_email" type="text" placeholder="john@gatech.edu" maxlength="50"/>
            </label>
        </div>

        <div class="large-4 columns end">
            <label>Phone Number
                <input id="in_app_phone" type="text" placeholder="2065421523" maxlength="10"/>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-2 columns">
            <label>GPA
                <input id="in_app_gpa" type="text" placeholder="2.0" maxlength="4"/>
            </label>
        </div>

        <div class="large-6 large-offset-2 columns end">
            <input type="radio" name="student_type" value="undergrad" id="undergrad"><label for="undergrad">Undergraduate</label>
            <input type="radio" name="student_type" value="grad" id="grad"><label for="grad">Graduate</label>
        </div>
    </div>

</fieldset>

<fieldset class="row">
    <legend>Courses for Tutoring</legend>

    <div id="course_list">

        <div id="tutor_course" class="row">
            <?php
            include "templates/course-selector.html";
            ?>

            <input id="cb_gta" name="cb_gta" type="checkbox"><label class="lb_gta" for="cb_gta">GTA</label>

        </div>
    </div>

    <!--        dynamically add more course to course_list via js-->
    <button id="btn_add_course" class="button tiny radius">Add More Course</button>

</fieldset>

<fieldset class="row">
    <legend>Available Days and Time</legend>
    <div id="tutor_calendar">
        <?php
        include "templates/calendar.html";
        ?>
    </div>

</fieldset>

<div class="row">
    <a href="#" id="btn_submit_app" class="button small radius">Submit Application</a>
</div>


<?php
include "templates/footer.php"
?>

<script>
    $(document).ready(function () {
        fetchCourseSchoolList("#tutor_course");
    });
</script>
