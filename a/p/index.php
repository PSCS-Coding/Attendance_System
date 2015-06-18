<?php
$admin = 1;
require_once('../../login.php');
?>
<!DOCTYPE html>
    <html>
        <head>
<?php require_once('header.php'); ?>
            <title>Admin Page</title>
        </head>
                <body>
                    <div id="TopHeader">
                    <h1 class="Myheader">Welcome Admin!</h1>
                    </div>
        <div id="navigation">
            <h1 class="header">Admin View</h1>
            <a href="../../" class="option">Back to Main Page</a>
            <a href="edit_students.php" class="option">Students</a>
            <a href="edit_facilitators.php" class="option">Facilitators</a>
            <a href="edit_allottedhours.php" class="option">Allotted Hours</a>
            <a href="edit_passwords.php" class="option">Passwords</a>
            <a href="edit_holidays.php" class="option">Holidays</a>
            <a href="edit_globals.php" class="option">Globals</a>
            <a href="edit_events.php" class="option">Events</a>
            <a href="edit_groups.php" class="option">Groups</a>
            <a href="offsite_stats.php" class="option">Offsite Stats</a>
            <a href="miscellaneous.php" class="option">Miscellaneous</a>
            <a href="example.php" class="option disabled">Beta</a>
                    </div>
     <div align="center" id="main">
                    </div>
                </body>

    </html>
