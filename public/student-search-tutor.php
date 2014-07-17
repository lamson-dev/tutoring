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


<div class="row">
    <a href="#" id="btn_show_avai_tutor" class="button small radius">Show Available Tutors</a>
</div>
<div id="avai_tutor" style="display: none;">
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
            <tr>
                <td>Son</td>
                <td>Nguyen</td>
                <td>son@gatech.edu</td>
                <td>4</td>
                <td>2</td>
                <td>4</td>
                <td>3</td>
            </tr>
            <tr>
                <td>Tue</td>
                <td>Tran</td>
                <td>tue@gatech.edu</td>
                <td>1</td>
                <td>5</td>
                <td>1</td>
                <td>6</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <a href="schedule-tutor.php" id="btn_schedule_tutor" class="button small radius">Schedule Tutor</a>
        <a href="main-menu.php" id="btn_cancel" class="button small radius">Cancel</a>
    </div>

</div>

<?php
include "templates/footer.php"
?>

<script>
    $(document).ready(function () {
        fetchCourseSchoolList("#student_search_course");
    });
</script>
