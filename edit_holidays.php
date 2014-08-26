<html>
<head>
<title>Admin: Edit Holidays</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/textbox.css" />
<link rel='stylesheet' href="css/pikaday.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
</head>
<body>
<h1>Edit Holidays</h1>
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
	//admin menu
       include 'admin-navbar.php';

// ADD A NEW HOLIDAY			
if (isset($_POST['addnew'])) {
$date = strtotime($_POST['date']);
$stmt = $db_server->prepare("INSERT INTO holidays (holidayname, date) VALUES (?, FROM_UNIXTIME(?))");
$stmt->bind_param('ss', $_POST['holidayname'] , $date);
$stmt->execute(); 
$stmt->close();
}				

// EDIT (UPDATE) A HOLIDAY
if (isset($_POST['save'])) {
 $date = strtotime($_POST['date']);
 $stmt = $db_server->prepare("UPDATE holidays SET holidayname = ? , date = FROM_UNIXTIME(?) WHERE id = ?");
	  $stmt->bind_param('ssi', $_POST['holidayname'], $date, $_POST['id']);
	  $stmt->execute(); 
	  $stmt->close();
	} 

// DELETE A HOLIDAY
if(isset($_POST['delete'])) {
	$stmt = $db_server->prepare("DELETE FROM holidays WHERE id = ?");
	$stmt->bind_param('i', $_POST['id']);
	$stmt->execute(); 		
	$stmt->close();
	}
	
	

// GET THE LIST OF HOLIDAYS
	$result = $db_server->query("SELECT * FROM holidays ORDER BY date");

?>

<form style="margin-bottom:1em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="holidayname" placeholder="Holiday Name" required><br />
	<input type="text" name="date" id="date" placeholder="Holiday Date" required><br />
	<input type="submit" name="addnew" value="Add Holiday" />
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
	

<table border="0">
 <table style="width:400px">
 
   <tr>
      <th style="text-align:left">Holiday Name</th>
      <th style="text-align:left">Date</th>
      <th style="text-align:left">Edit</th>
	  <th style="text-align:left">Delete</th>
   </tr>
<?php
// loop through list of names 
while ($list = mysqli_fetch_assoc($result)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['id']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['id'];
		if (isset($_POST[$editme])) { 
		$adjusteddate = new DateTime($list['date']);
		?> 
		<td><input type="text" name="holidayname" class="textbox" value="<?php echo $list['holidayname']; ?>" required></td>
		<td><input type="text" name="date" class="textbox" id="editdate" value="<?php echo $adjusteddate->format('m d Y'); ?>" required></td>
		<td><button type="submit" name="save" value="<?php echo $list['studentid']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $list['holidayname']; ?></td>
		<td><?php echo $list['date']; ?></td>
		
		<td><input type="submit" name="edit-<?php echo $list['id']; ?>" value="Edit"></td>
		<?php } ?>	
		<td><button type="submit" name="delete" value="<?php echo $list['holidayname']; ?>">Delete</button></td>
	</tr>
</form>
<?php 
} // end while
?>
</table>
  <!-- date picker javascript -->          
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('date') });
</script>
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('editdate') });
</script>
</body>
</html>