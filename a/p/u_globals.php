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
	<title>Example Page - Students</title>
	<?php require_once('header.php'); ?>
</head>
    
<body class="admin">
    
    <!-- HEADER BAR -->
    <div id="TopHeader"><h1>Update Global Data</h1></div>
    
<?php

// In-Code Refrences:
// B = Button
// Y - Year in school
// U - Update
// a - admin

//MYSQLI SELECT QUERY
$query_results = $mysqli->query("SELECT * FROM globals ORDER BY id");


// CHECKING IF THE "SAVE" BUTTON HAS BEEN CLICKED
if (!empty($_POST['Save'])) {
    
// DEFINING POST VARIABLES
$u_starttime = $_POST['U_starttime'];
$u_endtime = $_POST['U_endtime'];
$u_startdate = $_POST['U_startdate'];
$u_enddate = $_POST['U_enddate'];
$find_id = $_POST['gid'];

// QUERY DEFINING WHAT TO UPDATE
$query = "UPDATE globals SET starttime = ? , endtime = ? , startdate = ? , enddate = ? WHERE id = ?";
    
// PREPARE STATEMENT    
$statement = $mysqli->prepare($query);

//BIND parameters for markers
$results =  $statement->bind_param('ssssi', $u_starttime, $u_endtime, $u_startdate, $u_enddate, $find_id);
$statement->execute();
$statement->close();
// PRINTING SUSSESS OR ERROR
if($results){print 'Success! record updated'; }else{print 'Error : ('. $mysqli->errno .') '. $mysqli->error;}

// CLOSING ORIGIN IF STATEMENT   
}

?>
        
<!-- Start of main table -->
<table class="center">
    <th>Start Time</th>
    <th>End Time</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th class="textcenter">-</th>

<?php

// PUTTING SQL RESULTS INTO AN ARRAY
while($row = $query_results->fetch_array()) {
        
    // MAKING A SINGLE VAR FROM POST AND YEAR IN SCHOOL
    $editMode = "Update" . $row['id'];
    
    // CHECKING IF THERE IS POST DATA FOR $editMode
    if (empty($_POST[$editMode])) {
    
        // PRINTING TABLE ROW
            print '<tr>';
        // MAKING FORM
            print '<form action="u_globals.php" method="POST">';
        // GETS/MAKES HIDDEN GLOBALS ID
            print '<input type="hidden" name="gid" value="'.$row["id"].'">';
        // PRINTS START TIME
            print '<td>'.$row["starttime"].'</td>';
        // PRINTS END TIME
            print '<td>'.$row["endtime"].'</td>';
        // PRINTS START DATE
            print '<td>'.$row["startdate"].'</td>';
        // PRINTS END DATE
            print '<td>'.$row["enddate"].'</td>';
        // PRINTS UPDATE BUTTONS
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Update'.$row["id"].'" value="Update">
            </td>';
        // PRINTS FORM CLOSE
            print '</form>';
        // PRINTS END TABLE ROW
            print '</tr>';

    } else {
        
        // PRINTING STARTING TABLE ROW
            print '<tr>';
        // PRINTING STARTING FORM
            print '<form action="u_globals.php" method="POST">';
        // GETS/MAKES HIDDEN GLOBALS ID
            print '<input type="hidden" name="gid" value="'.$row["id"].'">';
        // PRINTS START TIME AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="10" name="U_starttime" value="'.$row["starttime"].'"></td>';
        // PRINTS END TIME AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="10" name="U_endtime" value="'.$row["endtime"].'"></td>';
        // PRINTS START DATE AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="10" name="U_startdate" value="'.$row["startdate"].'"></td>';
        // PRINTS END DATE AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="10" name="U_enddate" value="'.$row["enddate"].'"></td>';
        // UPDATE BUTTON
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Save" value="Save">
            </td>';
        // PRINTING CLOSE FORM
            print '</form>';
        // PRINTING END OF TABLE ROW
            print '</tr>';
    
    
}
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