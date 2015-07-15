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
// NN = New Name
// Y - Year in school
// U - Update
// NN - New Name
// d_ - Deactivated
// a - admin
// commhours - Community Hours
// offhours - Offsite Hours
// IS - Independent Study

//MYSQLI SELECT QUERY
$query_results = $mysqli->query("SELECT * FROM globals ORDER BY id");


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

?>
        
<!-- Start of main table -->
<table class="center">
    <th>Year in School</th>
    <th>Community Hours</th>
    <th>Offsite Hours</th>
    <th>IS Hours</th>
    <th class="textcenter">Change</th>

<?php

// PUTTING SQL RESULTS INTO AN ARRAY
while($row = $query_results->fetch_array()) {
        
    // MAKING A SINGLE VAR FROM POST AND YEAR IN SCHOOL
    $editMode = "Update" . $row['yis'];
    
    // CHECKING IF THERE IS POST DATA FOR $editMode
    if (empty($_POST[$editMode])) {
    
        // PRINTING TABLE ROW
            print '<tr>';
        // MAKING FORM
            print '<form action="u_allottedhours.php" method="POST">';
        // GETS/MAKES HIDDEN YEAR IN SCHOOL ID
            print '<input type="hidden" name="yid" value="'.$row["yis"].'">';
        // PRINTS YEAR IN SCHOOL
            print '<td>'.$row["yis"].'</td>';
        // PRINTS COMMUNITY HOURS
            print '<td>'.$row["communityhours"].'</td>';
        // PRINTS OFFSITE HOURS
            print '<td>'.$row["offsitehours"].'</td>';
        // PRINTS INDEPENDENT STUDY HOURS
            print '<td>'.$row["IShours"].'</td>';
        // PRINTS UPDATE BUTTONS
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Update'.$row["yis"].'" value="Update">
                <input type="submit" class="adminbtn" name="Delete" value="Delete">
            </td>';
        // PRINTS FORM CLOSE
            print '</form>';
        // PRINTS END TABLE ROW
            print '</tr>';

    } else {
        
        // PRINTING STARTING TABLE ROW
            print '<tr>';
        // PRINTING STARTING FORM
            print '<form action="u_allottedhours.php" method="POST">';
        // GETS/MAKES HIDDEN YEAR IN SCHOOL ID
            print '<input type="hidden" name="yid" value="'.$row["yis"].'">';
        // PRINTS YEAR IN SCHOOL (NOT EDITABLE)
            print '<td>'.$row["yis"].'</td>';
        // PRINTS COMMUNITY HOURS AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="5" name="U_communityhours" value="'.$row["communityhours"].'"></td>';
        // PRINTS OFFSITE HOURS AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="5" name="U_offsitehours" value="'.$row["offsitehours"].'"></td>';
        // PRINTS Independent Study hours as TEXTBOX
            print '<td><input type="text" class="aTextField" size="5" name="U_IShours" value="'.$row["IShours"].'"></td>';
        // UPDATE BUTTON
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Save" value="Save">
                <input type="submit" class="adminbtn" name="Delete" value="Delete">
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