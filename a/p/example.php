<?php require_once('../../login.php'); ?>
<html>
<head>
	<title>Example Page - Facilitators</title>
	<?php require_once('header.php'); ?>
</head>
<!--
Refrences:
B = Button
-->
<body class="adminpage">
    <div align="center">
<?php
//Open a new connection to the MySQL server
$mysqli = new mysqli('localhost','root','root','pscsorg_attendance');

//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

//MySqli Select Query
$results = $mysqli->query("SELECT facilitatorname, email, advisor FROM facilitators");
?>
<table style="width: 50%;">
    
    <th>Name</th>
    <th>Email</th>
    <th>Advisor</th>
    <th>Options</th>

    <?php
while($row = $results->fetch_assoc()) {
    print '<tr>';
    print '<td>'.$row["facilitatorname"].'</td>';
    print '<td>'.$row["email"].'</td>';
    print '<td>'.$row["advisor"].'</td>';
    print '<td><input type="submit" name="bOptions" value="O"></td>';
    print '</tr>';
}  

// Frees the memory associated with a result
$results->free();

// close connection 
$mysqli->close();
    ?>
    
</table>
    
    </div>
</body>
</html>