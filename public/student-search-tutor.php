<?php
include "templates/header.php"
?>
<div id="student_search_course" class="row">
    <?php include "templates/course-selector.html"; ?>
</div>

<div id="student_calendar">
    <?php
    include "templates/calendar.html";
    ?>
</div>


<!-- Triggers the modals -->
<div class="row">
    <a href="#" id="btn_show_avai_tutor" class="button small radius">Show Available Tutors</a>

    <a href="main-menu.php" id="btn_cancel" class="button small radius">Cancel</a>
</div>


<!-- Reveal Modals begin -->
<div id="avai_tutor_modal" class="reveal-modal" data-reveal>

    <div id="avai_tutor">
        <div class="row">
            <table>
                <thead>
                <tr>
                    <th width="100">First Name</th>
                    <th width="100">Last Name</th>
                    <th width="200">Email</th>
                    <th width="100">Avg Prof Rating</th>
                    <th width="100">Number of Professors</th>
                    <th width="100">Avg Student Rating</th>
                    <th width="100">Number of Students</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

        <div class="row">
            <a href="student-schedule-tutor.php" id="btn_schedule_tutor" class="button small radius">Schedule Tutor</a>
        </div>

    </div>

    <a class="close-reveal-modal">&#215;</a>

</div>
 <!-- Reveal Modals end -->


<?php
include "templates/footer.php"
?>

<script>
    $(document).ready(function () {
        fetchCourseSchoolList("#student_search_course");
    });
</script>
