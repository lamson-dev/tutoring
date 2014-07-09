<?php
include "templates/header.php";
?>

<!-- Add your site or application content here -->
<div class="row">

    <div class="large-6 columns">

        <div class="signup-panel">

            <p class="welcome">Welcome to Georgia Tech Tutor System!</p>

            <form>
                <div class="row collapse">
                    <div class="small-2  columns">
                        <span class="prefix"><i class="fi-torso-female"></i></span>
                    </div>
                    <div class="small-10  columns">
                        <input id="gtid" type="text" placeholder="GT-ID" value="902910000">
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-2 columns ">
                        <span class="prefix"><i class="fi-lock"></i></span>
                    </div>
                    <div class="small-10 columns ">
                        <input id="password" type="password" placeholder="Password">
                    </div>
                </div>
            </form>

            <a href="#" id="btn_login" class="button">Login</a>

        </div>
    </div>
</div>

<?php
include "templates/footer.php";
?>

