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
    <div id="TopHeader"><h1>Update Holidays</h1></div>
    
<?php

// In-Code Refrences:
// B = Button
// NN = New Name
// Y - Year in school
// U - Update
// NN - New Name
// d_ - Deactivated
// a - admin
// hname- Holiday Name
// hdate - Holiday Date

//MYSQLI SELECT QUERY
$query_results = $mysqli->query("SELECT * FROM holidays ORDER BY date");

/////// INSERT FUNCTION //////////
// CHECKING IF THE "ADD HOLIDAY" BUTTON HAS BEEN CLICKED
if (!empty($_POST['addnew'])) {
    
//VALUES TO BE INSERTED INTO THE HOLIDAY TABLE
$new_hname = '"'.$mysqli->real_escape_string('-Example').'"';
$new_hdate = '"'.$mysqli->real_escape_string('2014-11-10').'"';

//QUERY DEFINING WHAT TO INSERT
$insert_row = $mysqli->query("INSERT INTO holidays (holidayname, date) VALUES($new_hname, $new_hdate)");

// SUCCESS/ERROR MESSAGES
if($insert_row){print 'Success!'; }else{die('Error : ('. $mysqli->errno .') '. $mysqli->error);}

// CLOSING FOR ORIGIN IF STATEMENT
}

// CHECKING IF THE "SAVE" BUTTON HAS BEEN CLICKED
if (!empty($_POST['Save'])) {
    
// DEFINING POST VARIABLES
$u_hname = $_POST['U_holidayname'];
$u_hdate = $_POST['U_date'];
$find_id = $_POST['hid'];

// QUERY DEFINING WHAT TO UPDATE
$query = "UPDATE holidays SET holidayname = ? , date = ? WHERE id = ?";
    
// PREPARE STATEMENT    
$statement = $mysqli->prepare($query);

//BIND parameters for markers
$results =  $statement->bind_param('ssi', $u_hname, $u_hdate, $find_id);
$statement->execute();
$statement->close();
// PRINTING SUSSESS OR ERROR
if($results){print 'Success! record updated'; }else{print 'Error : ('. $mysqli->errno .') '. $mysqli->error;}

// CLOSING ORIGIN IF STATEMENT   
}


////////DELETE FUNCTION/////////
if (!empty($_POST['Delete'])) {

// PUTTING POST INTO A VARIABLE FOR QUERY
$holiday_id = $_POST['hid'];

//MYSQLI UPDATE(REMOVE) QUERY
$results = $mysqli->query("DELETE studentdata SET current='0' WHERE facilitatorid = $student_id");
}

?>
        
<!-- Start of main table -->
<table class="center">
    <th>Holiday</th>
    <th>Date</th>
    <th class="textcenter">Options</th>

<?php

// PUTTING SQL RESULTS INTO AN ARRAY
while($row = $query_results->fetch_array()) {
        
    // MAKING A SINGLE VAR FROM POST AND HOLIDAY ID
    $editMode = "Update" . $row['id'];
    
    // CHECKING IF THERE IS POST DATA FOR $editMode
    if (empty($_POST[$editMode])) {
    
        // PRINTING TABLE ROW
            print '<tr>';
        // MAKING FORM
            print '<form action="u_holidays.php" method="POST">';
        // GETS/MAKES HIDDEN HOLIDAY ID
            print '<input type="hidden" name="hid" value="'.$row["id"].'">';
        // PRINTS HOLIDAY NAME
            print '<td>'.$row["holidayname"].'</td>';
        // PRINTS HOLIDAY DATE
            print '<td>'.$row["date"].'</td>';
        // PRINTS UPDATE BUTTONS
            print '
            <td class="textcenter">
                <input type="submit" class="adminbtn" name="Update'.$row["id"].'" value="Update">
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
            print '<form action="u_holidays.php" method="POST">';
        // GETS/MAKES HIDDEN HOLIDAY ID
            print '<input type="hidden" name="hid" value="'.$row["id"].'">';
        // PRINTS HOLIDAY NAME AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="15" name="U_holidayname" value="'.$row["holidayname"].'"></td>';
        // PRINTS HOLIDAY DATE AS TEXTBOX
            print '<td><input type="text" class="aTextField" size="10" name="U_date" value="'.$row["date"].'"></td>';
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