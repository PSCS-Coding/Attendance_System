<?php
$admin = 1;
require_once('../../login.php'); ?>
<html>
<head>
	<title>Example Page - Facilitators</title>
	<?php require_once('header.php'); ?>
    	<script>
	$(function () {
		$('nav li ul').hide().removeClass('fallback');
		$('nav li').hover(function () {
			$('ul', this).stop().slideToggle(200);
		});
	});
	</script>
</head>
    
<!--
Refrences:
B = Button
NN = New Name
Y - Year in school
-->
    
<body class="admin">
                            <div id="TopHeader"><h1>Example Page</h1></div>
    <div align="center">
<?php
//MySqli Select Query
$query_results = $mysqli->query("SELECT * FROM studentdata WHERE current = '1' ORDER BY studentid");

if (!empty($_POST['Update'])) {
$mydesc = 'The best person';
echo $_POST['sid'];
$query = "UPDATE studentdata SET studentdesc=? WHERE studentid = ?";
$statement = $mysqli->prepare($query);

//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
$results =  $statement->bind_param('si', $mydesc, $_POST['sid']);

if($results){
    print 'Success! record updated (Updated: '.$mydesc.')'; 
}else{
    print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
}      
}

?>
        
<table style="width: 50%;">
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
            print '<td>'."N.A.".'</td>';
        // PRINTS YEAR IN SCHOOL
            print '<td>'.$row["yearinschool"].'</td>';
        // UPDATE BUTTON
            print '
            <td>
                <input type="submit" class="adminbtn" name="Update" value="Update">
                <input type="submit" class="adminbtn" name="Delete" value="Delete">
            </td>';
        // CLOSE FORM
            print '</form>';
        // END OF TABLE ROW
            print '</tr>';

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