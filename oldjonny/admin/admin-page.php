<!DOCTYPE html>
    <html>
        <head>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="loadtabs.js"></script>
            <title>Admin Page</title>
        </head>
                <body>
		    <?php
			session_start();

			$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
			
			//make this $_SESSION['adminSet'] if it's an admin-only page
			if(!$_SESSION['adminSet'])
				{
					header("location: ../../main_login.php");
				}
		?>
		
		
            <?php
         // set up mysql connection
	 require_once("../../connection.php");
	 require_once("../../function.php");
         ?>
	 <div align="center">
	    <h1>Welcome Admin!</h1>
            <button type="button" id="button1">Students</button>
	    <button type="button" id="button2">Facilitators</button>
	    <button type="button" id="button3">Allotted Hours</button>
	    <button type="button" id="button4">Passwords</button>
	    <button type="button" id="button5">Holidays</button>
	    <button type="button" id="button6">Globals</button>
            
<!----- ALL TABS/PAGES ON ADMIN PAGE ----->


<!----- STUDENTS PAGE ----->
<div id="showdiv1">
<?php
// Get Cast Page
    include_once("edit_students.php");	
?>
</div>

<!----- FACILITATORS PAGE ----->
<div id="showdiv2">
<?php
// Get Menu Page
    include_once("edit_facilitators.php");	
?>
</div>

<!----- ALLOTTED HOURS PAGE ----->
<div id="showdiv3">
<?php
// Get Orders Page
    include_once("edit_allottedhours.php");	
?>
</div>

<!----- PASSWORDS PAGE ----->
<div id="showdiv4">
<?php
// Get Menu Page
    include_once("edit_passwords.php");	
?>
</div>

<!----- HOLIDAYS PAGE ----->
<div id="showdiv5">
<?php
// Get Menu Page
    include_once("edit_holidays.php");	
?>
</div>

<!----- GLOBALS PAGE ----->
<div id="showdiv6">
<?php
// Get Menu Page
    include_once("edit_globals.php");	
?>
</div>
	 </div>
                </body>
        
        
        
        
        
        
        
        
        
        
        
    </html>