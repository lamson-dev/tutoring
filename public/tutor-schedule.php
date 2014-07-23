<?php
include "templates/header.php"
?>


    <div class="row">
        <div class="large-4 columns">

            <label>Tutor GT-ID
                <input id="tutor_gtid" type="text" placeholder="902910000" maxlength="9"/>
            </label>
        </div>

        <div class="large-3 columns end">
            <a href="#" id="btn_show_tutor_schedule" class="button small radius">Show Schedule</a>
        </div>
    </div>

    <div class="row">

    </div>


    <div id="tutor_schedule_calendar" class="row calendar" style="display: none">
        <div class=" large-10 columns">

            <h5 id="hello_tutor"></h4>

            <table class="calendar">

                <thead>
                <tr>
                    <th width="300">Mon</th>
                    <th width="300">Tue</th>
                    <th width="300">Wed</th>
                    <th width="300">Thu</th>
                    <th width="300">Fri</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td class="active">
                        <div id="tucal_Mon">
                        </div>
                    </td>
                    <td class="active">
                        <div id="tucal_Tue">
                        </div>
                    </td>
                    <td class="active">
                        <div id="tucal_Wed">
                            <!-- <span class="event blue">2pm - ECE 2031 - Celine Irvene [celine@gatech.edu]</span> -->
                        </div>
                    </td>
                    <td>
                        <div id="tucal_Thu">
                            <!-- <span class="event yellow">8am - ISyE 3770 - Son Nguyen [son@gatech.edu]</span> -->

                        </div>
                    </td>
                    <td>
                        <div id="tucal_Fri">
                        </div>
                    </td>
                </tr>

                </tbody>

            </table>

        </div>
    </div>


<?php
include "templates/footer.php"
?>

<script>
    $(document).ready(function() {
        updateTutorName();
    });
</script>
