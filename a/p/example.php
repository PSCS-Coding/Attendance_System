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
//Open a new connection to the MySQL server
$mysqli = new mysqli('localhost','root','root','pscsorg_attendance');

//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

//MySqli Select Query
$results = $mysqli->query("SELECT * FROM studentdata WHERE current = '1' ORDER BY firstname");

?>
<table style="width: 50%;">
    
    <th>Name</th>
    <th>Enrolled</th>
    <th>Advisor</th>
    <th>Y</th>
    <th>Options</th>

<?php
    while($row = $results->fetch_assoc()) {
    
        // CONVERTS FIRST & LAST NAME INTO 1 VAR
            $NN_first = $row["firstname"];
            $NN_last = substr($row["lastname"], 0, 1);
            $NN_full = $NN_first.' '.$NN_last;
    
        // MAKING ENROLLED DATE LOOK NICE
            $new_date_format = new DateTime($row['startdate']);
    
        // PRINTING PLAIN DATA
        print '<tr>';
        // PRINTS FULL NAME
        print '<td>'.$NN_full.'</td>';
        // PRINTS ENROLLED YEAR
        print '<td>'.$new_date_format->format('M, Y').'</td>';
        // PRINTS ADVISOR
        print '<td>'."N.A.".'</td>';
        // PRINTS YEAR IN SCHOOL
        print '<td>'.$row["yearinschool"].'</td>';
        print '<td><input type="submit" class="adminbtn" value="BUTTON"></td>';
        // PRINTS OPTIONS BUTTON
        print '<td>	<div class="wrapper">
		<div id="main">
			<nav>
				<ul>
					<li>
						<a href="#">&nabla;</a>
						<ul class="fallback">
							<li><a href="#">Update Info</a></li>
							<li><a href="#">Delete</a></li>
							<li><a href="#">(DEV)</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div></td>';
        // END OF TABLE ROW
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