<html>
<body>         
<?php
// EDIT ALLOTTED HOURS
if (isset($_POST['saveallotted'])) {
    $updatehours = $db_server->prepare("UPDATE allottedhours SET communityhours = ? , offsitehours = ? , IShours = ? WHERE id = ?");
    $updatehours->bind_param('iiii', $_POST['communityhours'], $_POST['offsitehours'], $_POST['IShours'], $_POST['saveallotted']); 
    $updatehours->execute(); 
    $updatehours->close();
} 

// Query for allotted hours
$hoursresult = $db_server->query("SELECT * FROM allottedhours ORDER BY yis");
?>
    
<div class="allottedhours">
<table>
   <tr>
      <th>Year In School</th>
      <th>Community Hours</th>
      <th>Offsite Hours</th>
      <th>IS Hours</th>
	  <th>Edit</th>
   </tr>
<?php
// Make list of allotted hours
while ($hourslist = mysqli_fetch_assoc($hoursresult)) { ?>
<tr>
<form action="?p=Allotted-Hours" method="post">
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
</div>
</body>
</html>