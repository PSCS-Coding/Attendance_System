<html>
<head>
<title>Admin: Edit The Student Table</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
</head>
<body>
<h1>[ADMIN] Edit Students</h1>
<?php
// set up mysql connection
	require_once("../connection.php");
//function document
	require_once("function.php");

// ADD A NEW STUDENT			
if (isset($_POST['addnew'])) {
$timestamp = strtotime($_POST['startdate']);
$stmt = $db_server->prepare("INSERT INTO studentdata (firstname, lastname, startdate) VALUES (?, ?, FROM_UNIXTIME(?))");
$stmt->bind_param('sss', $_POST['newfirstname'] , $_POST['newlastname'] , $timestamp);
$stmt->execute(); 
$stmt->close();
}				

// form handling: reactivate a student
	if (!empty($_POST['activate']) && !empty($_POST['activateid']))
	{
		$activateid  = get_post('activateid');
		
		$stmt = $db_server->prepare("UPDATE studentdata SET current = 1 WHERE studentid = ?");
		$stmt->bind_param('s', $activateid);
		$stmt->execute(); 		
		$stmt->close();
	}
	
// EDIT (UPDATE) A STUDENT
if (isset($_POST['save'])) {
 $timestamp = strtotime($_POST['editstartdate']);
 $stmt = $db_server->prepare("UPDATE studentdata SET firstname = ? , lastname = ? , startdate = FROM_UNIXTIME(?) , yearinschool = ? WHERE studentid = ?");
	  $stmt->bind_param('ssssi', $_POST['firstname'], $_POST['lastname'], $timestamp, $_POST['yearinschool'], $_POST['id']);
	  $stmt->execute(); 
	  $stmt->close();
	} 

// DELETE A STUDENT
if(isset($_POST['delete'])) {
	$stmt = $db_server->prepare("UPDATE studentdata SET current = 0 WHERE studentid = ?");
	$stmt->bind_param('i', $_POST['id']);
	$stmt->execute(); 		
	$stmt->close();
	}
	
	

// GET THE LIST OF Students
	$result = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");

?>

<form style="margin-bottom:1em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="newfirstname" placeholder="First Name" required><br />
	<input type="text" name="newlastname" placeholder="Last Name" required><br />
	<input type="text" name="startdate" id="startdate" placeholder="Start Date" required/><br />
	<select name='advisor'>Selected:<?php echo $list['advisor']; ?><option>Advisor</option>
	        <?php
		     $advisorget = $db_server->query("SELECT * FROM facilitators WHERE advisor='Yes' ORDER BY advisor ASC");
		     
		      while ($advisor_option = $advisorget->fetch_assoc()) {
			
			if ($list['facilitatorname'] == $advisorget->fetch_assoc()){
			
			?><option selected value= '<?php echo $advisor_option['facilitatorname']; ?> '> <?php echo $advisor_option['advisor']; ?></option><?php
			
			} else {
			
			?>  <option value= '<?php echo $advisor_option['facilitatorname']; ?> '> <?php echo $advisor_option['advisor']; ?></option> <?php
			
			}
		      }
			?>
	        </select><br/>
	<input type="submit" name="addnew" value="Add Student" />
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
<table style="width:550px">
	
   <tr>
      <th style="text-align:left">Name</th>
      <th style="text-align:left">Last Name</th>
      <th style="text-align:left">Start Date</th>
      <th style="text-align:left">YIS</th>
      <th style="text-align:left">Advisor</th>
      <th style="text-align:left">Edit</th>
	  <th style="text-align:left">NEW-WORD</th>
   </tr>
<?php
// loop through list of names 
while ($list = mysqli_fetch_assoc($result)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['studentid']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['studentid'];
		if (isset($_POST[$editme])) { 
		$adjusteddate = new DateTime($list['startdate']);?> 
		<td><input type="text" name="firstname" class="textbox" value="<?php echo $list['firstname']; ?>" required></td>
		<td><input type="text" name="lastname" class="textbox" value="<?php echo $list['lastname']; ?>" required></td>
		<td><input type="text" name="editstartdate" id="editstartdate" class="textbox" value="<?php echo $adjusteddate->format('m d Y'); ?>" required></td>
		<td><select name='yearinschool'>Selected:<?php echo $list['yearinschool']; ?></option>
	        <?php
		     $yearget = $db_server->query("SELECT * FROM allottedhours ORDER BY yearinschool ASC");
		     
		      while ($year_option = $yearget->fetch_assoc()) {
			
			if ($list['yearinschool'] == $yearget->fetch_assoc()){
			
			?><option selected value= '<?php echo $year_option['id']; ?> '> <?php echo $year_option['yis']; ?></option><?php
			
			} else {
			
			?>  <option value= '<?php echo $year_option['id']; ?> '> <?php echo $year_option['yis']; ?></option> <?php
			
			}
		      }
			?>
	        </select></td>
		<td><button type="submit" name="save" value="<?php echo $list['studentid']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $list['firstname']; ?></td>
		<td><?php echo $list['lastname']; ?></td>
		<td><?php echo $list['startdate']; ?></td>
		<td><?php echo $list['yearinschool']; ?></td>
		<td><?php echo $list['advisor']; ?></td>
		
		<td><input type="submit" name="edit-<?php echo $list['studentid']; ?>" value="Edit"></td>
		<?php } ?>	
		<td><button type="submit" name="delete" value="<?php echo $list['firstname']; ?>">X</button></td>
	</tr>
<?php 
} // end while
?>
</table>
</form>
<?php
// query to get all deleted students
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
	<?php
// close the mysqli session	
	$db_server->close();
	
	function get_post($var) {
		return mysql_real_escape_string($_POST[$var]);
	}
			?>
            
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