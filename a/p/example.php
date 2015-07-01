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
	<title>Example Page - Facilitators</title>
	<?php require_once('header.php'); ?>
</head>
    
<body class="admin">
    
    <!-- HEADER BAR -->
    <div id="TopHeader"><h1>Example Page</h1></div>
    
    <div align="scenter">
<?php

// In-Code Refrences:
// B = Button
// NN = New Name
// Y - Year in school
// U - Update
// NN - New Name

//MySqli Select Query
$query_results = $mysqli->query("SELECT * FROM studentdata WHERE current = '1' ORDER BY studentid");

if (!empty($_POST['Save'])) {
    
// DEFINING POST VARIABLES
$u_first = $_POST['U_firstname'];
$u_last = $_POST['U_lastname'];
$u_enrolled = $_POST['U_enrolled'];
$u_advisor = $_POST['U_advisor'];
$u_yis = $_POST['U_yis'];
$find_id = $_POST['sid'];

// QUERY DEFINING WHAT TO UPDATE
$query = "UPDATE studentdata SET firstname = ? , lastname = ? , startdate = ? , advisor = ? , yearinschool = ? WHERE studentid = ?";
    
// PREPARE STATEMENT    
$statement = $mysqli->prepare($query);

//BIND parameters for markers
$results =  $statement->bind_param('ssssii', $u_first, $u_last, $u_enrolled, $u_advisor, $u_yis, $find_id);

// PRINTING SUSSESS OR ERROR
if($results){print 'Success! record updated'; }else{print 'Error : ('. $mysqli->errno .') '. $mysqli->error;}

// CLOSING ORIGIN IF STATEMENT
}

?>
        
<table class="center">
    <th>Name</th>
    <th>Enrolled</th>
    <th>Advisor</th>
    <th>Y</th>
    <th>Options</th>

<?php

while($row = $query_results->fetch_array()) {

        // CONVERTS FIRST & LAST NAME INTO 1 VAR
            $NN_first = $row["firstname"];
            $NN_last = substr($row["lastname"], 0, 1);
            $NN_full = $NN_first.' '.$NN_last;
    
        // MAKING ENROLLED DATE LOOK NICE
            $new_date_format = new DateTime($row['startdate']);
        
        // IF STATEMENT FOR UPDATING
    $editMode = "Update" . $row['studentid'];
    if (empty($_POST[$editMode])) {
    
        // PRINTING PLAIN DATA
            print '<tr>';
        // MAKING FORM
            print '<form action="example.php" method="POST">';
        // GETS STUDENT ID
            print '<input type="hidden" name="sid" value="'.$row["studentid"].'">';
        // PRINTS FULL NAME
            print '<td>'.$NN_full.'</td>';
        // PRINTS ENROLLED YEAR
            print '<td>'.$new_date_format->format('M, Y').'</td>';
        // PRINTS ADVISOR
            print '<td>'.$row["advisor"].'</td>';
        // PRINTS YEAR IN SCHOOL
            print '<td>'.$row["yearinschool"].'</td>';
        // UPDATE BUTTON
            print '
            <td>
                <input type="submit" class="adminbtn" name="Update'.$row["studentid"].'" value="Update">
                <input type="submit" class="adminbtn" name="Delete" value="Delete">
            </td>';
        // CLOSE FORM
            print '</form>';
        // END OF TABLE ROW
            print '</tr>';

    } else {
        
        // PRINTING PLAIN DATA
            print '<tr>';
        // MAKING FORM
            print '<form action="example.php" method="POST">';
        // GETS STUDENT ID
            print '<input type="hidden" name="sid" value="'.$row["studentid"].'">';
        // PRINTS FIRST & LAST NAME
            print '<td>
            <input type="text" class="aTextField" size="10" name="U_firstname" value="'.$row["firstname"].'">   
            <input type="text" class="aTextField" size="10" name="U_lastname" value="'.$row["lastname"].'">
            </td>';
        // PRINTS ENROLLED YEAR
            print '<td><input type="text" class="aTextField" size="10" name="U_enrolled" value="'.$row["startdate"].'"></td>';
        // PRINTS ADVISOR
            print '<td><input type="text" class="aTextField" size="5" name="U_advisor" value="'.$row["advisor"].'"></td>';
        // PRINTS YEAR IN SCHOOL
            print '<td><input type="text" class="aTextField" size="3" name="U_yis" value="'.$row["yearinschool"].'"></td>';
        // UPDATE BUTTON
            print '
            <td>
                <input type="submit" class="adminbtn" name="Save" value="Save">
                <input type="submit" class="adminbtn" name="Delete" value="Delete">
            </td>';
        // CLOSE FORM
            print '</form>';
        // END OF TABLE ROW
            print '</tr>';
    
    
}
}

// Frees the memory associated with a result
$query_results->free();

// close connection
$mysqli->close();

?>
    
</table>
    </div>
</body>
</html>