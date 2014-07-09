<?php
include "templates/header.php"
?>

    <fieldset class="row">
        <legend>Student</legend>
        <a href="search-tutor.php" id="btn_search_tutor" class="button">Search/Schedule Tutor</a>
        <a href="rate-tutor.php" id="btn_rate_tutor" class="button">Rate a Tutor</a>

    </fieldset>

    <fieldset class="row">
        <legend>Tutor</legend>
        <a href="tutor-apply.php" id="btn_apply" class="button">Apply</a>
        <a href="tutor-schedule" id="btn_find_schedule" class="button">Find My Schedule</a>

    </fieldset>

    <fieldset class="row">
        <legend>Professor</legend>
        <a href="prof-recommendation.php" id="btn_recommend" class="button">Add Recommendation</a>

    </fieldset>

    <fieldset class="row">
        <legend>Admin</legend>
        <a href="admin-summary1.php" id="btn_summary_one" class="button">Summary #1</a>
        <a href="admin-summary2.php" id="btn_summary_two" class="button">Summary #2</a>
    </fieldset>



<?php
include "templates/footer.php"
?>