<?php
include "templates/header.php"
?>

    <div class="row">
        <div class="large-2 columns">
            <label>
                <select>
                    <option disabled>Department</option>
                    <option value="CS">CS</option>
                    <option value="ISyE">ISyE</option>
                    <option value="ECE">ECE</option>
                </select>
            </label>

        </div>

        <div class="large-2 columns end">
            <label>
                <select>
                    <option disabled>Course Number</option>
                    <option value="4400">4400</option>
                    <option value="2110">2110</option>
                    <option value="1331">1331</option>
                </select>
            </label>

        </div>

    </div>

    <div class="row calendar">
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
                            <span class="day">29</span>
                        </div>
                    </td>
                    <td class="inactive">
                        <div>
                            <span class="day">30</span>
                        </div>
                    </td>
                    <td class="active">
                        <div>
                            <span class="day">31</span>
                            <span class="event blue">2pm</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="day">1</span>
                            <span class="event yellow">8am</span>
                            <span class="event yellow">10am</span>

                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="day">2</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="day">3</span>
                            <span class="event yellow">9am</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="day">4</span>
                        </div>
                    </td>
                </tr>

                </tbody>

            </table>

        </div>
    </div>

    <div class="row">
        <a href="#" id="btn_show_avai_tutor" class="button">Show Available Tutors</a>
    </div>

    <div class="row">
        <a href="schedule-tutor.php" id="btn_schedule_tutor" class="button">Schedule Tutor</a>
        <a href="main-menu.php" id="btn_cancel" class="button">Cancel</a>
    </div>


<?php
include "templates/footer.php"
?>