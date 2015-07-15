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
    <div id="TopHeader"><h1>Update Students</h1></div>
    
<?php

// In-Code Refrences:
// B = Button
// NN = New Name
// Y - Year in school
// U - Update
// NN - New Name
// d_ - Deactivated

//MYSQLI SELECT QUERY
$query_results = $mysqli->query("SELECT * FROM studentdata WHERE current = '1' ORDER BY firstname");

/////// INSERT FUNCTION //////////
// CHECKING IF THE "ADD STUDENT" BUTTON HAS BEEN CLICKED
if (!empty($_POST['addnew'])) {
    
//VALUES TO BE INSERTED INTO THE STUDENT DATA TABLE
$first_name = '"'.$mysqli->real_escape_string('-Example').'"';
$last_name = '"'.$mysqli->real_escape_string('Person').'"';
$start_date = '"'.$mysqli->real_escape_string('2009-09-01').'"';
$advisor = '"'.$mysqli->real_escape_string('Nic').'"';

//QUERY DEFINING WHAT TO INSERT
$insert_row = $mysqli->query("INSERT INTO studentdata (firstname, lastname, startdate, advisor) VALUES($first_name, $last_name, $start_date, $advisor)");

// SUCCESS/ERROR MESSAGES
if($insert_row){print 'Success!'; }else{die('Error : ('. $mysqli->errno .') '. $mysqli->error);}

// CLOSING FOR ORIGIN IF STATEMENT
}

// CHECKING IF THE "SAVE" BUTTON HAS BEEN CLICKED
if (!empty($_POST['Save'])) {
    
// DEFINING POST VARIABLES
$u_first = $_POST['U_firstname'];
$u_last = $_POST['U_lastname'];
$u_enrolled = $_POST['U_enrolled'];
$u_advisor = $_POST['U_advisor'];
$u_yis = $_POST['U_yis'];
$find_id = $_POST['sid'];
echo $u_first;
echo $find_id;

// QUERY DEFINING WHAT TO UPDATE
$query = "UPDATE studentdata SET firstname = ? , lastname = ? , startdate = ? , advisor = ? , yearinschool = ? WHERE studentid = ?";
    
// PREPARE STATEMENT    
$statement = $mysqli->prepare($query);

//BIND parameters for markers
$results =  $statement->bind_param('ssssii', $u_first, $u_last, $u_enrolled, $u_advisor, $u_yis, $find_id);
$statement->execute();
$statement->close();
// PRINTING SUSSESS OR ERROR
if($results){print 'Success! record updated'; }else{print 'Error : ('. $mysqli->errno .') '. $mysqli->error;}

// CLOSING ORIGIN IF STATEMENT   
}


////////DELETE FUNCTION/////////
if (!empty($_POST['Delete'])) {

// PUTTING POST INTO A VARIABLE FOR QUERY
$student_id = $_POST['sid'];

//MYSQLI UPDATE(REMOVE) QUERY
$results = $mysqli->query("UPDATE studentdata SET current='0' WHERE studentid = $student_id");
}

?>
        
<!-- Start of main table -->
<table class="center">
    <th>Name</th>
    <th>Enrolled</th>
    <th>Advisor</th>
    <th>Y</th>
    <th class="textcenter">Options</th>

<?php

// PUTTING SQL RESULTS INTO AN ARRAY
while($row = $query_results->fetch_array()) {

        // CONVERTS FIRST & LAST NAME INTO A SINGLE VARIABLE
            $NN_first = $row["firstname"];
            $NN_last = substr($row["lastname"], 0, 1);
            $NN_full = $NN_first.' '.$NN_last;
    
        // PUTTNIG ENROLLED DATE INTO A NEW DATETIME & VARIABLE
            $new_date_format = new DateTime($row['startdate']);
        
    // MAKING A SINGLE VAR FROM POST AND STUDENT ID
    $editMode = "Update" . $row['studentid'];
    
    // CHECKING IF THERE IS POST DATA FOR $editMode
    if (empty($_POST[$editMode])) {
    
        // PRINTING TABLE ROW
            print '<tr>';
        // MAKING FORM
            print '<form action="example.php" method="POST">';
        // GETS/MAKES HIDDEN STUDENT ID
            print '<input type="hidden" name="sid" value="'.$row["studentid"].'">';
        // PRINTS FULL NAME VARIABLE
            print '<td>'.$NN_full.'</td>';
        // PRINTS ENROLLED YEAR
            print '<td>'.$new_date_format->format('M, Y').'</td>';
        // PRINTS ADVISOR
            print '<td>'.$row["advisor"].'</td>';
        // PRINTS YEAR IN SCHOOL
            print '<td>'.$row["yearinschool"].'</td>';
        // PRINTS UPDATE BUTTON
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Update'.$row["studentid"].'" value="Update">
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
            print '<form action="example.php" method="POST">';
        // GETS/MAKES HIDDEN STUDENT ID
            print '<input type="hidden" name="sid" value="'.$row["studentid"].'">';
        // PRINTS FIRST & LAST NAME AS TEXTBOXES
            print '<td>
            <input type="text" class="aTextField" size="10" name="U_firstname" value="'.$row["firstname"].'">   
            <input type="text" class="aTextField" size="10" name="U_lastname" value="'.$row["lastname"].'">
            </td>';
        // PRINTS ENROLLED YEAR AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="10" name="U_enrolled" value="'.$row["startdate"].'"></td>';
        // PRINTS ADVISOR AS DROPDOWN (COMING SOON)
            print '<td><input type="text" class="aTextField" size="7" name="U_advisor" value="'.$row["advisor"].'"></td>';
        // PRINTS YEAR IN SCHOOL AS DROPDOWN (COMING SOON)
            print '<td><input type="text" class="aTextField" size="3" name="U_yis" value="'.$row["yearinschool"].'"></td>';
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


?>

<!-- CLOSE FOR MAIN TABLE -->
</table>
    
<!-- OPENING FOR DELETED STUDETS TABLE -->
    <table class="center tabletwo">
        
    <th>First</th>
    <th>Last</th>
    <th>Enrolled</th>
    <th class="textcenter">Revive</th>
        
    <?php

// QUERY FOR DELETED STUDENTS
$d_query_results = $mysqli->query("SELECT * FROM studentdata WHERE current = '0' ORDER BY firstname");

// PUTTING SQL RESULTS INTO AN ARRAY
while($d_row = $d_query_results->fetch_array()) {
    
        // PUTTNIG ENROLLED DATE INTO A NEW DATETIME & VARIABLE
            $d_new_date_format = new DateTime($d_row['startdate']);
        // MAKING FORM
            print '<form action="example.php" method="POST">';
        // PRINTING TABLE ROW
            print '<tr>';
        // GETS/MAKES HIDDEN STUDENT ID
            print '<input type="hidden" name="d_sid" value="'.$d_row["studentid"].'">';
        // PRINTS FIRST NAME
            print '<td>'.$d_row["firstname"].'</td>';
        // PRINTS LAST NAME
            print '<td>'.$d_row["lastname"].'</td>';
        // PRINTS ENROLLED YEAR
            print '<td>'.$d_new_date_format->format('M, Y').'</td>';
        // PRINTS UPDATE BUTTON
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Revive" value="Revive">
            </td>';
        // PRINTS END TABLE ROW
            print '</tr>';
        // PRINTS FORM CLOSE
            print '</form>';
}

////////REVIVE FUNCTION/////////
// CHECKS IF REVIVE BUTTON HAS BEEN CLICKED
 if (!empty($_POST['Revive'])) {

    // PUTTING POST INTO A VARIABLE FOR QUERY
    $d_student_id = $_POST['d_sid'];
     
    //MYSQLI UPDATE(REVIVE) QUERY
    $d_results = $mysqli->query("UPDATE studentdata SET current='1' WHERE studentid = $d_student_id");

}

// Frees the memory associated with a result
$d_query_results->free();

// close connection
$mysqli->close();

?>
        
        
        
    </table>
</body>
</html>