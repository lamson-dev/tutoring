<?php
include "templates/header.php"
?>

    <fieldset class="row">
        <legend>Academic Year 2014</legend>

        <div class="row">
            <div id="summary2_checkboxes" class="large-4 columns">
                <input id="sem_fall2" type="checkbox"><label for="sem_fall2">Fall</label>
                <input id="sem_spring2" type="checkbox"><label for="sem_spring2">Spring</label>
                <input id="sem_summer2" type="checkbox"><label for="sem_summer2">Summer</label>
            </div>

            <a href="#" id="btn_show_summary2" class="button small radius">Show Summary</a>
            <a href="main-menu.php" class="button small radius">Cancel</a>
        </div>
    </fieldset>


    <div id="tb_admin_sum2" class="row">
        <table>
            <thead>
            <tr>
                <th width="100">Course</th>
                <th width="100">Semester</th>
                <th width="100">TA</th>
                <th width="100">Average Rating</th>
                <th width="100">NonTA</th>
                <th width="100">Average Rating</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>CS 4400</td>
                <td>Fall</td>
                <td>1</td>
                <td>4</td>
                <td>2</td>
                <td>3</td>
            </tr>
            <tr>
                <td></td>
                <td>Spring</td>
                <td>2</td>
                <td>6</td>
                <td>2</td>
                <td>6</td>
            </tr>
            <tr>
                <td></td>
                <td>Average</td>
                <td></td>
                <td>6</td>
                <td></td>
                <td>6</td>
            </tr>
            </tbody>
        </table>
    </div>

<?php
include "templates/footer.php"
?>
