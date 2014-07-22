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
                        <input id="gtid" type="text" maxlength="9" placeholder="GT-ID" value="902111001">
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-2 columns ">
                        <span class="prefix"><i class="fi-lock"></i></span>
                    </div>
                    <div class="small-10 columns ">
                        <input id="password" type="password" placeholder="Password" value="1001">
                    </div>
                </div>
            </form>

            <a href="#" id="btn_login" class="button radius">Login</a>

        </div>
    </div>
</div>

<?php
include "templates/footer.php";
?>
