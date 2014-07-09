<?php
include "templates/header.php"
?>

    <fieldset class="row">
        <legend>Student Information</legend>

        <div class="row">
            <div class="large-4 columns end">
                <label>GaTech ID
                    <input type="text" placeholder="902910000"/>
                </label>
            </div>
        </div>

        <div class="row">
            <div class="large-4 columns">
                <label>First Name
                    <input type="text" placeholder="John"/>
                </label>
            </div>

            <div class="large-4 columns end">
                <label>Last Name
                    <input type="text" placeholder="Smith"/>
                </label>
            </div>
        </div>

        <div class="row">
            <div class="large-4 columns">
                <label>Email
                    <input type="text" placeholder="john@gatech.edu"/>
                </label>
            </div>

            <div class="large-4 columns end">
                <label>Phone Number
                    <input type="text" placeholder="2065421523"/>
                </label>
            </div>
        </div>

        <div class="row">
            <div class="large-2 columns">
                <label>GPA
                    <input type="text" placeholder="2.0"/>
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

    </fieldset>

    <fieldset class="row">
        <legend>Available Days and Time</legend>
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

    </fieldset>

    <div class="row">
        <a href="main-menu.php" id="btn_submit_app" class="button small radius">Submit Application</a>
    </div>



<?php
include "templates/footer.php"
?>