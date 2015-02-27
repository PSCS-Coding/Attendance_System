<!DOCTYPE html>
    <html>
        <head>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="js/HideTabs.js"></script>
	    <link rel='stylesheet' href="css/pikaday.css" />
        <link rel='stylesheet' href="css/adminpage.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<link rel="shortcut icon" type="image/png" href="img/mobius.png"/>
            <title>Admin Page</title>
        </head>
                <body style="background-color: dimgray;">
    <div id="puttheimagehere" style="position: fixed; opacity: 0.5; z-index: -1;">
	<img src="img/mobius.png">
    </div>
		
		
            <?php
         // set up mysql connection
     $userlevel = "admin";
     require_once("../login.php");
	 require_once("../connection.php");
	 require_once("../function.php");
         ?>
                    <div id="TopHeader">
                    <h1 class="Myheader">Welcome Admin!</h1>
                    </div>
        <div id="navigation">
            <h1 class="header">2.0.1 DEV</h1>
            <a href="../" class="option">Back to Main Page</a>
            <a href="p/Students" class="option">Students</a>
            <a href="p/Facilitators" class="option">Facilitators</a>
            <a href="p/Allotted-Hours" class="option">Allotted Hours</a>
            <a href="p/Passwords" class="option">Passwords</a>
            <a href="p/Holidays" class="option">Holidays</a>
            <a href="p/Globals" class="option">Globals</a>
            <a href="p/Events" class="option">Events</a>
            <a href="p/Groups" class="disabled">Groups</a>
            <a href="p/Stats" class="disabled">Student Stats</a>
                    </div>            
	 <div align="center" id="main">
                    </div>
                </body>
        
    </html>