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
    <div id="TopHeader"><h1>Update Facilitators</h1></div>
    
<?php

// In-Code Refrences:
// B = Button
// NN = New Name
// Y - Year in school
// U - Update
// NN - New Name
// d_ - Deactivated

//MYSQLI SELECT QUERY
$query_results = $mysqli->query("SELECT * FROM facilitators ORDER BY facilitatorname");

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
$u_name = $_POST['U_name'];
$u_email = $_POST['U_email'];
$u_advisor = $_POST['U_advisor'];
$find_id = $_POST['fid'];

// QUERY DEFINING WHAT TO UPDATE
$query = "UPDATE studentdata SET facilitatorname = ? , email = ? , advisor = ? WHERE facilitatorid = ?";
    
// PREPARE STATEMENT    
$statement = $mysqli->prepare($query);

//BIND parameters for markers
$results =  $statement->bind_param('ssii', $u_name, $u_email, $u_advisor, $find_id);
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
    <th>Email</th>
    <th>Advisor</th>
    <th class="textcenter">Options</th>

<?php

// PUTTING SQL RESULTS INTO AN ARRAY
while($row = $query_results->fetch_array()) {
        
    // MAKING A SINGLE VAR FROM POST AND STUDENT ID
    $editMode = "Update" . $row['facilitatorid'];
    
    // CHECKING IF THERE IS POST DATA FOR $editMode
    if (empty($_POST[$editMode])) {
    
        // PRINTING TABLE ROW
            print '<tr>';
        // MAKING FORM
            print '<form action="u_facilitators.php" method="POST">';
        // GETS/MAKES HIDDEN STUDENT ID
            print '<input type="hidden" name="fid" value="'.$row["facilitatorid"].'">';
        // PRINTS ADVISOR NAME
            print '<td>'.$row["facilitatorname"].'</td>';
        // PRINTS EMAIL
            print '<td>'.$row["email"].'</td>';
        // PRINTS ADVISOR
            print '<td>'.$row["advisor"].'</td>';
        // PRINTS UPDATE BUTTON
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Update'.$row["facilitatorid"].'" value="Update">
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
            print '<form action="u_facilitators.php" method="POST">';
        // GETS/MAKES HIDDEN STUDENT ID
            print '<input type="hidden" name="fid" value="'.$row["facilitatorid"].'">';
        // PRINTS FIRST & LAST NAME AS TEXTBOXES
            print '<td><input type="text" class="aTextField" size="10" name="U_name" value="'.$row["facilitatorname"].'"></td>';
        // PRINTS EMAIL AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="15" name="U_email" value="'.$row["email"].'"></td>';
        // PRINTS AVDISOR AS DROPDOWN
            print '<td><input type="text" class="aTextField" size="3" name="U_advisor" value="'.$row["advisor"].'"></td>';
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

?>
        
        
        
    </table>
</body>
</html>