<?php
include "templates/header.php"
?>

    <div class="row">
        <div class="medium-2 columns">
            <label>
                <select id="search_school_list">
                    <option disabled>Department</option>
                    <!--                    <option value="CS">CS</option>-->
                    <!--                    <option value="ISyE">ISyE</option>-->
                    <!--                    <option value="ECE">ECE</option>-->
                </select>
            </label>

        </div>

        <div class="medium-2 columns end">
            <label>
                <select id="search_number_list">
                    <option disabled>Course Number</option>
                    <!--                    <option value="4400">4400</option>-->
                    <!--                    <option value="2110">2110</option>-->
                    <!--                    <option value="1331">1331</option>-->
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
                    <td class="active">
                        <div>
                            <span class="event blue">9am</span>
                            <span class="event blue">10am</span>
                            <span class="event green">11am</span>
                            <span class="event green">12pm</span>
                            <span class="event blue">1pm</span>
                            <span class="event blue">2pm</span>
                            <span class="event blue">3pm</span>
                            <span class="event blue">4pm</span>
                        </div>
                    </td>
                    <td class="active">
                        <div>
                            <span class="event green">9am</span>
                            <span class="event green">10am</span>
                            <span class="event blue">11am</span>
                            <span class="event blue">12pm</span>
                            <span class="event blue">1pm</span>
                            <span class="event blue">2pm</span>
                            <span class="event blue">3pm</span>
                            <span class="event blue">4pm</span>
                        </div>
                    </td>
                    <td class="active">
                        <div>
                            <span class="event blue">9am</span>
                            <span class="event blue">10am</span>
                            <span class="event blue">11am</span>
                            <span class="event blue">12pm</span>
                            <span class="event green">1pm</span>
                            <span class="event green">2pm</span>
                            <span class="event green">3pm</span>
                            <span class="event green">4pm</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="event blue">9am</span>
                            <span class="event green">10am</span>
                            <span class="event green">11am</span>
                            <span class="event blue">12pm</span>
                            <span class="event blue">1pm</span>
                            <span class="event blue">2pm</span>
                            <span class="event blue">3pm</span>
                            <span class="event blue">4pm</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="event blue">9am</span>
                            <span class="event blue">10am</span>
                            <span class="event blue">11am</span>
                            <span class="event blue">12pm</span>
                            <span class="event green">1pm</span>
                            <span class="event green">2pm</span>
                            <span class="event blue">3pm</span>
                            <span class="event blue">4pm</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="event green">9am</span>
                            <span class="event green">10am</span>
                            <span class="event blue">11am</span>
                            <span class="event blue">12pm</span>
                            <span class="event blue">1pm</span>
                            <span class="event blue">2pm</span>
                            <span class="event green">3pm</span>
                            <span class="event green">4pm</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="event blue">9am</span>
                            <span class="event blue">10am</span>
                            <span class="event green">11am</span>
                            <span class="event green">12pm</span>
                            <span class="event green">1pm</span>
                            <span class="event blue">2pm</span>
                            <span class="event blue">3pm</span>
                            <span class="event blue">4pm</span>
                        </div>
                    </td>
                </tr>

                </tbody>

            </table>

        </div>
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