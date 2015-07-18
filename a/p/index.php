<?php
$admin = 1;
require_once('../../login.php');
/////////////////////////////////////////////
//                                         //
//         CREATED BY ANTHONY REYES        //
//       Puget Sound Community School      //
//                                         //
/////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Allotted Hours</title>
	<?php require_once('header.php'); ?>
</head>
    
<body class="admin">
    
    <!-- HEADER BAR -->
    <div id="TopHeader">
                    <h1 class="Myheader">Welcome 'username'!</h1>
</div>
    
<?php

// In-Code Refrences:
// B = Button
// U - Update
// a - admin
// commhours - Community Hours
// offhours - Offsite Hours
// IS - Independent Study
// noA - no add

// CHECKING IF THE "SAVE" BUTTON HAS BEEN CLICKED
if (!empty($_POST['Save'])) {
    
// DEFINING POST VARIABLES
$u_commhours = $_POST['U_communityhours'];
$u_offhours = $_POST['U_offsitehours'];
$u_ishours = $_POST['U_IShours'];
$find_id = $_POST['yid'];

// QUERY DEFINING WHAT TO UPDATE
$query = "UPDATE allottedhours SET communityhours = ? , offsitehours = ? , IShours = ? WHERE yis = ?";
    
// PREPARE STATEMENT    
$statement = $mysqli->prepare($query);

//BIND parameters for markers
$results =  $statement->bind_param('ssii', $u_commhours, $u_offhours, $u_ishours, $find_id);
$statement->execute();
$statement->close();
// PRINTING SUSSESS OR ERROR
if($results){print 'Success! record updated'; }else{print 'Error : ('. $mysqli->errno .') '. $mysqli->error;}

// CLOSING ORIGIN IF STATEMENT   
}

//MYSQLI SELECT QUERY
$query_results = $mysqli->query("SELECT * FROM events WHERE NOT (statusid = 8 OR statusid = 1) ORDER BY eventid LIMIT 5");

?>
        
<!-- Start of main table -->
<table class="center noA">
    <th>Name</th>
    <th>Status</th>
    <th>Info</th>
    <th>Time</th>

<?php

// PUTTING SQL RESULTS INTO AN ARRAY
while($row = $query_results->fetch_array()) {
    
    // CHECKING IF THERE IS POST DATA FOR $editMode
    
        // PRINTING TABLE ROW
            print '<tr>';
        // MAKING FORM
            print '<form action="" method="POST">';
        // GETS/MAKES HIDDEN YEAR IN SCHOOL ID
            print '<input type="hidden" name="eid" value="'.$row["eventid"].'">';
        // PRINTS YEAR IN SCHOOL
            print '<td>'.$row["studentid"].'</td>';
        // PRINTS COMMUNITY HOURS
            print '<td>'.$row["statusid"].'</td>';
        // PRINTS COMMUNITY HOURS
            print '<td>'.$row["info"].'</td>';
        // PRINTS OFFSITE HOURS
            print '<td>'.$row["timestamp"].'</td>';
        // PRINTS FORM CLOSE
            print '</form>';
        // PRINTS END TABLE ROW
            print '</tr>';

}

// Frees the memory associated with a result
$query_results->free();

// close connection
$mysqli->close();

?>

<!-- CLOSE FOR MAIN TABLE -->
</table>
        
        
        
    </table>
</body>
</html>