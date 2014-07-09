<?php
include "templates/header.php"
?>



    <div class="row">
        <h4>Select Tutor for CS 4400</h4>
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
                            <span class="event blue">2pm - Celine Irvene [celine@gatech.edu]</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="day">1</span>
                            <span class="event yellow">8am - Son Nguyen [son@gatech.edu]</span>
                            <span class="event yellow">10am - Tue Tran [tue@gatech.edu</span>

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
                            <span class="event yellow">9am - Kate Unsworth [kate@gatech.edu]</span>
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
        <a href="main-menu.php" id="btn_schedule_selected_tutor" class="button">Schedule Selected Tutor</a>
        <a href="search-tutor.php" id="btn_cancel" class="button">Cancel</a>
    </div>
<?php
include "templates/footer.php"
?>