<html>
<body>
<h1 class="headerr">Edit Facilitators</h1>

<?php 
//Edit Facilitators
if (isset($_POST['addnewfacilitator'])) {
    $stmt = $db_server->prepare("INSERT INTO facilitators (facilitatorname, email) VALUES (?, ?)");
    $stmt->bind_param('ss', $_POST['newfacilitatorname'] , $_POST['newfacilitatoremail']);
    $stmt->execute(); 
    $stmt->close();				
}

// EDIT A Facilitator
if (isset($_POST['savefacilitator'])) {
    $stmt = $db_server->prepare("UPDATE facilitators SET facilitatorname = ? , email = ? WHERE facilitatorid = ?");
    $stmt->bind_param('ssi', $_POST['facilitatorname'], $_POST['email'], $_POST['id']);
    $stmt->execute(); 
    $stmt->close();
} 

// DELETE A FACILITATOR
if(isset($_POST['deletefacilitator'])) {
$stmt = $db_server->prepare("DELETE FROM facilitators WHERE facilitatorid = ?");
$stmt->bind_param('i', $_POST['id']);
$stmt->execute(); 		
$stmt->close();
}
	
	

// GET THE LIST OF FACILITATORS
	$facilitatorsresult = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname");
?>
    
<div class="facilitators">
<form style="margin-bottom:1em;" action="?p=Facilitators" method="post">
	<input type="text" name="newfacilitatorname" placeholder="Facilitator Name" required>
	<input type="text" name="newfacilitatoremail" placeholder="Facilitator Email">
	<input type="submit" name="addnewfacilitator" value="Add Facilitator" />
</form>
    
<table>
   <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Edit</th>
	  <th>Delete</th>
   </tr>
<?php
// Make list of all facilitators 
while ($facilitatorlist = mysqli_fetch_assoc($facilitatorsresult)) { ?>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $facilitatorlist['facilitatorid']; ?>">
	<tr>
		<?php $editme = "editfacilitators-" . $facilitatorlist['facilitatorid'];
		if (isset($_POST[$editme])) { 
		?> 
		<td><input type="text" name="facilitatorname" class="textbox" value="<?php echo $facilitatorlist['facilitatorname']; ?>" required></td>
		<td><input type="text" name="email" class="textbox" value="<?php echo $facilitatorlist['email']; ?>"></td>

		<td><button type="submit" name="savefacilitator" value="<?php echo $facilitatorlist['facilitatorid']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $facilitatorlist['facilitatorname']; ?></td>
		<td><?php echo $facilitatorlist['email']; ?></td>
		
		<td><input type="submit" name="editfacilitators-<?php echo $facilitatorlist['facilitatorid']; ?>" value="Edit"></td>
		<?php } ?>	
		<td><button type="submit" name="deletefacilitator" value="<?php echo $facilitatorlist['facilitatorname']; ?>">Delete</button></td>
	</tr>
</form>
<?php 
} // end while
?>
</table>
</div>
</body>
</html>