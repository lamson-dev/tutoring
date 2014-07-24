<?php
include "templates/header.php"
?>

    <fieldset class="row">
        <legend>Academic Year 2014</legend>

        <div class="row">
            <div id="summary1_checkboxes" class="large-4 columns">
                <input id="sem_fall" type="checkbox" value="Fall"><label for="sem_fall">Fall</label>
                <input id="sem_spring" type="checkbox" value="Spring"><label for="sem_spring">Spring</label>
                <input id="sem_summer" type="checkbox" value="Summer"><label for="sem_summer">Summer</label>
            </div>

            <a href="#" id="btn_show_summary1" class="button small radius">Show Summary</a>
            <a href="main-menu.php" class="button small radius">Cancel</a>
        </div>
    </fieldset>

    <div id="tb_admin_sum1" class="row" style="display: none;">
        <table>
            <thead>
            <tr>
                <th width="100">Course</th>
                <th width="100">Semester</th>
                <th width="100">Number of Students</th>
                <th width="100">Number of Tutors</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <!-- <td>CS 4400</td>
                <td>Fall</td>
                <td>3</td>
                <td>5</td> -->
            </tr>
            <tr>
                <!-- <td></td>
                <td>Spring</td>
                <td>2</td>
                <td>6</td> -->
            </tr>
            </tbody>
        </table>
    </div>


<?php
include "templates/footer.php"
?>
