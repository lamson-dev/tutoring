<?php
include "templates/header.php"
?>



<div class="row">
    <h4>Select Tutor for <span id="selected_course"></span></h4>
</div>

<div id="tutor_avai_calendar" class="row calendar">
    <div class=" large-10 columns">

        <table class="calendar">

            <thead>
            <tr>
                <th width="300">Mon</th>
                <th width="300">Tue</th>
                <th width="300">Wed</th>
                <th width="300">Thu</th>
                <th width="300">Fri</th>
                <th width="300">Sat</th>
                <th width="300">Sun</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="inactive">
                    <div>
                        <!-- <span class="day">29</span>-->
                    </div>
                </td>
                <td class="inactive">
                    <div>
                        <!-- <span class="day">30</span>-->
                    </div>
                </td>
                <td class="active">
                    <div>
                        <!-- <span class="day">31</span>-->
                            <span class="event gray">
                                <label class="time">2pm</label>
                                <label class="name">Celine Irvene</label>
                                <label class="email">[celine@gatech.edu]</label>
                            </span>
                    </div>
                </td>
                <td>
                    <div>
                        <!-- <span class="day">1</span>-->
                        <span class="event gray">
                            <label class="time">2pm</label>
                            <label class="name">Son Nguyen</label>
                            <label class="email">[son@gatech.edu]</label>
                        </span>
                        <span class="event gray">
                            <label class="time">10am</label>
                            <label class="name">Tue Tran</label>
                            <label class="email">[tue@gatech.edu]</label>
                        </span>

                    </div>
                </td>
                <td>
                    <div>
                        <!-- <span class="day">2</span>-->
                    </div>
                </td>
                <td>
                    <div>
                        <!-- <span class="day">3</span>-->
                        <span class="event gray">
                            <label class="time">10am</label>
                            <label class="name">Kate Unsworth</label>
                            <label class="email">[kate@gatech.edu]</label>
                        </span>
                    </div>
                </td>
                <td>
                    <div>
                        <!-- <span class="day">4</span>-->
                    </div>
                </td>
            </tr>

            </tbody>

        </table>

    </div>
</div>

<div class="row">
    <a href="#" id="btn_schedule_selected_tutor" class="button small radius">Schedule Selected Tutor</a>
    <a href="student-search-tutor.php" id="btn_cancel" class="button small radius">Cancel</a>
</div>

<?php
include "templates/footer.php"
?>

<script>
    $(document).ready(function () {
        $("#selected_course").text(courseSchoolSelected + " " + courseNumberSelected);
        disableMultipleSlotsSelection("#tutor_avai_calendar");
    });
</script>