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

// Reactivate Student
if (isset($_POST['Reactivate'])) {
    $stmt = $db_server->prepare("UPDATE studentdata SET current = 1 WHERE studentid = ?");
    $stmt->bind_param('i', $_POST['id2']);
    $stmt->execute(); 
    $stmt->close();
} 

// Query to get all deleted students
	$result2 = $db_server->query("SELECT * FROM studentdata WHERE current = 0 ORDER BY firstname ASC");

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
      <th>First</th>
      <th>Last</th>
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

<br />    
<table>
   <tr>
      <th>First</th>
      <th>Last</th>
      <th>Start Date</th>
      <th>Reactivate</th>
   </tr>
<?php  
    while ($list2 = mysqli_fetch_assoc($result2)) { ?>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <input type="hidden" name="id2" value="<?php echo $list2['studentid']; ?>">
            <tr>
        <td><?php echo $list2['firstname']; ?></td>
		<td><?php echo $list2['lastname']; ?></td>
		<td><?php echo $list2['startdate']; ?></td>
        <td><button type="submit" name="Reactivate" value="<?php echo $list2['studentid']; ?>">Reactivate</button></td>
                </tr>
        </form>
    <?php } ?>
    </table>
</div>
</body>
</html>