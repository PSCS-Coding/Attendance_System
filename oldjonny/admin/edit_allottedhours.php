<html>
<body>
<h1>Edit Allotted Hours</h1>

<?php
// EDIT (UPDATE) ALLOTTED HOURS
if (isset($_POST['saveallotted'])) {
 $updatehours = $db_server->prepare("UPDATE allottedhours SET communityhours = ? , offsitehours = ? , IShours = ? WHERE id = ?");
	  $updatehours->bind_param('iiii', $_POST['communityhours'], $_POST['offsitehours'], $_POST['IShours'], $_POST['saveallotted']); 
	  $updatehours->execute(); 
	  $updatehours->close();
	} 

	

// GET THE LIST OF ALLOTTED HOURS
	$hoursresult = $db_server->query("SELECT * FROM allottedhours ORDER BY yis");

?>
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
<table style="width:600px">
   <tr>
      <th style="text-align:left">Year In School</th>
      <th style="text-align:left">Community Hours</th>
      <th style="text-align:left">Offsite Hours</th>
      <th style="text-align:left">IS Hours</th>
	  <th style="text-align:left">Edit</th>
   </tr>
<?php
// loop through list of allotted hours
while ($hourslist = mysqli_fetch_assoc($hoursresult)) { ?>
<tr>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $hourslist['id']; ?>">

		<?php $editme = "edithours-" . $hourslist['id'];
		if (isset($_POST[$editme])) { ?>
		<td><?php echo $hourslist['yis']; ?></td>
		<td><input type="text" name="communityhours" class="textbox" value="<?php echo $hourslist['communityhours']; ?>" required></td>
		<td><input type="text" name="offsitehours" class="textbox" value="<?php echo $hourslist['offsitehours']; ?>" required></td>
                <td><input type="text" name="IShours" class="textbox" value="<?php echo $hourslist['IShours']; ?>" required></td>
		<td><button type="submit" name="saveallotted" value="<?php echo $hourslist['id']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $hourslist['yis']; ?></td>
		<td><?php echo $hourslist['communityhours']; ?></td>
                <td><?php echo $hourslist['offsitehours']; ?></td>
		<td><?php echo $hourslist['IShours']; ?></td>
		
		<td><input type="submit" name="edithours-<?php echo $hourslist['id']; ?>" value="Edit"></td>
		<?php } ?>
		</form>
		</tr>
<?php 
} // end while
?>
</table>
</body>
</html>