<html>
<body>
<h1 class="headerr">Edit Students</h1>
 
<!-- UPDATE FUNCTIONS -->     
<?php 
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
    $stmt = $db_server->prepare("UPDATE studentdata SET firstname = ? , lastname = ? , startdate = FROM_UNIXTIME(?) , yearinschool = ?      WHERE studentid = ?");
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
	
//Reactivate Student
if (!empty($_POST['activate']) && !empty($_POST['activateid']))
{
    $activateid  = get_post('activateid');
    $stmt = $db_server->prepare("UPDATE studentdata SET current = 1 WHERE studentid = ?");
    $stmt->bind_param('s', $activateid);
    $stmt->execute(); 		
    $stmt->close();
}
	
// Query for student list
	$studentresult = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname"); ?>
    
<div class="students">
<form style="margin-bottom:1em;" action="?p=Students" method="post">
	<input type="text" name="newfirstname" placeholder="First Name" required>
	<input type="text" name="newlastname" placeholder="Last Name" required>
	<input type="text" name="startdate" id="startdate" placeholder="Start Date" required/>
	<input type="submit" name="addnewstudent" value="Add Student" />
</form>
<table>
   <tr>
      <th>Name</th>
      <th>Last Name</th>
      <th>Start Date</th>
      <th>YIS</th>
      <th>Edit</th>
	  <th>Hide</th>
   </tr>
<?php
// loop through list of names 
while ($list = mysqli_fetch_assoc($studentresult)) { ?>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['studentid']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['studentid'];
		if (isset($_POST[$editme])) { 
		$adjusteddate = new DateTime($list['startdate']);?> 
		<td><input type="text" name="firstname" class="textbox" value="<?php echo $list['firstname']; ?>" required></td>
		<td><input type="text" name="lastname" class="textbox" value="<?php echo $list['lastname']; ?>" required></td>
		<td><input type="text" name="editstartdate" id="editstartdate" class="textbox" value="<?php echo $adjusteddate->format('m d Y'); ?>" required></td>
		<td>
			<select name='yearinschool'>
	        <?php
				 $yearget = $db_server->query("SELECT yis FROM allottedhours ORDER BY yis ASC");
				 while ($year_option = $yearget->fetch_assoc()) {
					if ($list['yearinschool'] == $year_option['yis']) { ?>
						<option selected value="<?php echo $year_option['yis']; ?>"><?php echo $year_option['yis']; ?></option>
					<?php } else { ?>  
						<option value="<?php echo $year_option['yis']; ?>"> <?php echo $year_option['yis']; ?></option> 
				<?php
					}
			     }
			?>
	        </select>
		</td>
				
		<td><button type="submit" name="savestudent" value="<?php echo $list['studentid']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $list['firstname']; ?></td>
		<td><?php echo $list['lastname']; ?></td>
		<td><?php echo $list['startdate']; ?></td>
		<td><?php echo $list['yearinschool']; ?></td>
		
		<td><input type="submit" name="edit-<?php echo $list['studentid']; ?>" value="Edit"></td>
		<?php } ?>	
		<td><button type="submit" name="deletestudent" value="<?php echo $list['studentid']; ?>">X</button></td>
	</tr>
	</form>
<?php 
} // end while for student data
?>
</table>

<?php
// Query to get all deleted students
	$result = $db_server->query("SELECT * FROM studentdata WHERE current = 0 ORDER BY firstname ASC");
	$rows = $result->num_rows;
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
		$row = $result->fetch_assoc();
	?>
	
	<?php
		echo "<tr>";
		echo "<td>" . $row['firstname'] . " " . $row['lastname'] . "</td>" ;
		echo "<td>" . $row['startdate'] . "</td>";
		echo "<td>";
	
	?>
<!-- Reactivate Student -->	
        
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <input type="hidden" name="activate" id="activate" value="yes" />
    <input type="hidden" name="activateid" id="activateid" value="<?php echo $row['studentid']; ?>" />
    <input type="submit" value="REACTIVATE" />
</form>    
</td>
</tr>
	<?php } ?> 
    </table>
<?php
function get_post($var) {
return mysql_real_escape_string($_POST[$var]);
}
?>
</div>
</body>
</html>