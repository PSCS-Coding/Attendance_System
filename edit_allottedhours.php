<html>
<head>
<title>Admin: Edit Allotted Hours Table</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
     
</head>
<body>
<h1>[ADMIN] Edit Allotted Hours</h1>
<?php
	session_start();

	$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
	
	//make this $_SESSION['adminSet'] if it's an admin-only page
	if(!$_SESSION['adminSet'])
	{
		header("location: main_login.php");
	}
?>
<?php
// set up mysql connection
	require_once("../connection.php");
//function document
	require_once("function.php");

// EDIT (UPDATE) GLOBALS
if (isset($_POST['save'])) {
 $stmt = $db_server->prepare("UPDATE allottedhours SET communityhours = ? , offsitehours = ? , IShours = ? WHERE id = ?");
	  $stmt->bind_param('iiii', $_POST['communityhours'], $_POST['offsitehours'], $_POST['IShours'], $_POST['save']); 
	  $stmt->execute(); 
	  $stmt->close();
	} 

	

// GET THE LIST OF GLOBAL
	$result = $db_server->query("SELECT * FROM allottedhours ORDER BY yis");

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
// loop through list of names 
while ($list = mysqli_fetch_assoc($result)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['id']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['id'];
		if (isset($_POST[$editme])) { ?>
		<td><?php echo $list['yis']; ?></td>
		<td><input type="text" name="communityhours" class="textbox" value="<?php echo $list['communityhours']; ?>" required></td>
		<td><input type="text" name="offsitehours" class="textbox" value="<?php echo $list['offsitehours']; ?>" required></td>
                <td><input type="text" name="IShours" class="textbox" value="<?php echo $list['IShours']; ?>" required></td>
		<td><button type="submit" name="save" value="<?php echo $list['id']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $list['yis']; ?></td>
		<td><?php echo $list['communityhours']; ?></td>
                <td><?php echo $list['offsitehours']; ?></td>
		<td><?php echo $list['IShours']; ?></td>
		
		<td><input type="submit" name="edit-<?php echo $list['id']; ?>" value="Edit"></td>
		<?php } ?>
	</tr>
</form>
<?php 
} // end while
?>
</table>
</body>
</html>