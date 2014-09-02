<html>
<head>
<title>Admin: Edit The Student Table</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
</head>
<body>
<h1>Edit Students</h1>
<?php
require_once("../function.php");
require_once("../../connection.php");
		// ADD A NEW STUDENT			
		if (isset($_POST['addnewstudent'])) {
		$timestamp = strtotime($_POST['startdate']);
		$stmt = $db_server->prepare("INSERT INTO studentdata (firstname, lastname, startdate) VALUES (?, ?, FROM_UNIXTIME(?))");
		$stmt->bind_param('sss', $_POST['newfirstname'] , $_POST['newlastname'] , $timestamp);
		$stmt->execute(); 
		$stmt->close();
		}				

	
		// EDIT (UPDATE) A STUDENT
		if (isset($_POST['savestudent'])) {
		$timestamp = strtotime($_POST['editstartdate']);
		$stmt = $db_server->prepare("UPDATE studentdata SET firstname = ? , lastname = ? , startdate = FROM_UNIXTIME(?) , yearinschool = ? WHERE studentid = ?");
		$stmt->bind_param('sssii', $_POST['firstname'], $_POST['lastname'], $timestamp, $_POST['yearinschool'], $_POST['id']);
		$stmt->execute(); 
		$stmt->close();
		} 

	
		// DELETE A STUDENT
		if(isset($_POST['deletestudent'])) {
		$stmt = $db_server->prepare("UPDATE studentdata SET current = 0 WHERE studentid = ?");
		$stmt->bind_param('i', $_POST['id']);
		$stmt->execute();
		$stmt->close();
		}
	
	
		// form handling: reactivate a student
		if (!empty($_POST['activate']) && !empty($_POST['activateid']))
		{
		$stmt = $db_server->prepare("UPDATE studentdata SET current = 1 WHERE studentid = ?");
		$stmt->bind_param('s', $activateid);
		$stmt->execute(); 		
		$stmt->close();
		}
	
	
	
	

        // GET THE LIST OF Students
	$resultstudents = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");

?>


<form style="margin-bottom:1em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="newfirstname" placeholder="First Name" required>
	<input type="text" name="newlastname" placeholder="Last Name" required>
	<input type="text" name="startdate" id="startdate" placeholder="Start Date" required/>
	<input type="submit" name="addnewstudent" value="Add Student" />
</form>
	 <style> 
.textbox { 
    background-color: #BDD7F1; 
    border: solid 1px #646464; 
    outline: none; 
    padding: 2px 2px;
tr:nth-child(even)
{
background:#F7F7F7;
}    
} 
tr:nth-child(odd)
{
background:#E7E7FF;
}
table
{
border-spacing:0px;
}
</style>
<table style="width:550px">
	
   <tr>
      <th style="text-align:left">Name</th>
      <th style="text-align:left">Last Name</th>
      <th style="text-align:left">Start Date</th>
      <th style="text-align:left">YIS</th>
      <th style="text-align:left">Edit</th>
	  <th style="text-align:left">Hide</th>
   </tr>
   
<?php
// loop through student names
while ($studentlist = mysqli_fetch_assoc($resultstudents)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $studentlist['studentid']; ?>">
	<tr>
		<?php $editme = "editstudent-" . $studentlist['studentid'];
		if (isset($_POST[$editme])) { 
		$adjusteddate = new DateTime($studentlist['startdate']);?>
		
		
		<td><input type="text" name="firstname" class="textbox" value="<?php echo $studentlist['firstname']; ?>" required></td>
		<td><input type="text" name="lastname" class="textbox" value="<?php echo $studentlist['lastname']; ?>" required></td>
		<td><input type="text" name="editstartdate" id="editstartdate" class="textbox" value="<?php echo $adjusteddate->format('m d Y'); ?>" required></td>
		
		
		
		<td>
			<select name='yearinschool'>
	        <?php
				 $yearget = $db_server->query("SELECT yis FROM allottedhours ORDER BY yis ASC");
				 while ($year_option = $yearget->fetch_assoc()) {
					if ($studentlist['yearinschool'] == $year_option['yis']) { ?>
						<option selected value="<?php echo $year_option['yis']; ?>"><?php echo $year_option['yis']; ?></option>
					<?php } else { ?>  
						<option value="<?php echo $year_option['yis']; ?>"> <?php echo $year_option['yis']; ?></option> 
				<?php
					}
			     }
			?>
	        </select>
		</td>
			
				
		<td><button type="submit" name="savestudent" value="<?php echo $studentlist['studentid']; ?>">Save</button></td>
		
		
		<?php } else { ?>
		
		
		<td><?php echo $studentlist['firstname']; ?></td>
		<td><?php echo $studentlist['lastname']; ?></td>
		<td><?php echo $studentlist['startdate']; ?></td>
		<td><?php echo $studentlist['yearinschool']; ?></td>
		
		<td><input type="submit" name="editstudent-<?php echo $studentlist['studentid']; ?>" value="Edit"></td>
		<?php } ?>	
		<td><button type="submit" name="deletestudent" value="<?php echo $studentlist['studentid']; ?>">X</button></td>
	</tr>
	
<?php 
} // end while
?>
</form>
</table>


<?php
// query to get all deleted students
	$delresult = $db_server->query("SELECT * FROM studentdata WHERE current = 0 ORDER BY firstname ASC");
	$rows = $delresult->num_rows;
	
	
	
	
	?>
	
	 
	
    <h2>Deactivated Students</h2>
	<table class='table'>
		<tr>
        <th class='table_head'> Student Name </th>
	<th class='table_head'> Start Date </th>
	<th class='table_head'> Reactivate Student </th>
        </tr>
	<?php
		for ($j = 0 ; $j < $rows ; ++$j)
		{
		$row = $delresult->fetch_assoc();
	?>
	
	<?php
		echo "<tr>";
		echo "<td>" . $row['firstname'] . " " . $row['lastname'] . "</td>" ;
		echo "<td>" . $row['startdate'] . "</td>";
		echo "<td>";
	
	?>
<!-- Button to reactivate a student -->	
    <form action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post">
      <input type="hidden" name="activate" value="yes" />
      <input type="hidden" name="activateid" value="<?php echo $row['studentid']; ?>" />
      <input type="submit" value="REACTIVATE" />
    </form>
    </td>
    </tr>
	<?php } ?> 
    </table> 
  <!-- date picker javascript -->          
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('startdate') });
</script>
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('editstartdate') });
</script>
</body>
</html>