<html>
<head>
<body>
<h1>Edit Facilitators</h1>
<?php
	
if (isset($_POST['addnewfacilitator'])) {
$stmt = $db_server->prepare("INSERT INTO facilitators (facilitatorname, email) VALUES (?, ?)");
$stmt->bind_param('ss', $_POST['newfacilitatorname'] , $_POST['newfacilitatoremail']);
$stmt->execute(); 
$stmt->close();				
}

// EDIT (UPDATE) A Facilitator
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

<form style="margin-bottom:1em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="newfacilitatorname" placeholder="Facilitator Name" required>
	<input type="text" name="newfacilitatoremail" placeholder="Facilitator Email">
	<input type="submit" name="addnewfacilitator" value="Add Facilitator" />
</form>
	 <style> 
.textbox { 
    background-color: #BDD7F1; 
    border: solid 1px #646464; 
    outline: none; 
    padding: 2px 2px; 
} 
tr:nth-child(odd)
{
background:#E7E7FF;
}
tr:nth-child(even)
{
background:#F7F7F7;
}
table
{
border-spacing:0px;
}
</style>
<table style="width:500px">
   <tr>
      <th style="text-align:left">Name</th>
      <th style="text-align:left">Email</th>
      <th style="text-align:left">Edit</th>
	  <th style="text-align:left">Delete</th>
   </tr>
<?php
// loop through facilitators 
while ($facilitatorlist = mysqli_fetch_assoc($facilitatorsresult)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
</body>
</html>