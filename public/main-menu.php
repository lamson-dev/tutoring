<?php
include "templates/header.php"
?>

    <fieldset id="menu_student" class="row">
        <legend>Student</legend>
        <a href="student-search-tutor.php" id="btn_search_tutor" class="button small radius">Search/Schedule Tutor</a>
        <a href="student-rate-tutor.php" id="btn_rate_tutor" class="button small radius">Rate a Tutor</a>

    </fieldset>

    <fieldset id="menu_tutor" class="row">
        <legend>Tutor</legend>
        <a href="tutor-apply.php" id="btn_tutor_apply" class="button small radius">Apply</a>
        <a href="tutor-schedule.php" id="btn_find_schedule" class="button small radius">Show My Schedule</a>

    </fieldset>

    <fieldset id="menu_professor" class="row">
        <legend>Professor</legend>
        <a href="prof-recommendation.php" id="btn_recommend" class="button small radius">Add Recommendation</a>

    </fieldset>

    <fieldset id="menu_admin" class="row">
        <legend>Admin</legend>
        <a href="admin-summary1.php" id="btn_summary_one" class="button small radius">Summary #1</a>
        <a href="admin-summary2.php" id="btn_summary_two" class="button small radius">Summary #2</a>
    </fieldset>


<?php
include "templates/footer.php"
?>

<script>
    $(document).ready(function() {
       showMenuBasedOnUserType();
    });
</script>