<?php
include "templates/header.php"
?>


    <div class="row">
        <div class="large-4 columns">
            <label>Tutor GT-ID
                <input type="text" placeholder="902910000"/>
            </label>
        </div>

        <div class="large-3 columns end">
            <a href="#" id="btn_show_tutor_schedule" class="button small radius">Show Schedule</a>
        </div>
    </div>

    <div class="row">
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
                        </div>
                    </td>
                    <td class="inactive">
                        <div>
                        </div>
                    </td>
                    <td class="active">
                        <div>
                            <span class="event blue">2pm - ECE 2031 - Celine Irvene [celine@gatech.edu]</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="event yellow">8am - ISyE 3770 - Son Nguyen [son@gatech.edu]</span>
                            <span class="event yellow">10am - CS 4400 - Tue Tran [tue@gatech.edu</span>

                        </div>
                    </td>
                    <td>
                        <div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="event yellow">9am - CS 4400 - Kate Unsworth [kate@gatech.edu]</span>
                        </div>
                    </td>
                    <td>
                        <div>
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