<html>
<head>
<body>
<h1>Edit Facilitators</h1>
<?php
			session_start();

			$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
			
			//make this $_SESSION['adminSet'] if it's an admin-only page
			if(!$_SESSION['set'])
				{
					header("location: main_login.php");
			}
		?>
<?php
// set up mysql connection
	require_once("../connection.php");
//function document
	require_once("function.php");

// ADD A NEW Facilitator			
if (isset($_POST['addnew'])) {
		if($_POST['advisor']=="Advisor?"){
				echo "Please choose an advisor status.";
		} else {
$stmt = $db_server->prepare("INSERT INTO facilitators (facilitatorname, email, advisor) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $_POST['newfacilitatorname'] , $_POST['newfacilitatoremail'] , $_POST['advisor']);
$stmt->execute(); 
$stmt->close();
		}
}				

// EDIT (UPDATE) A Facilitator
if (isset($_POST['save'])) {
 $stmt = $db_server->prepare("UPDATE facilitators SET facilitatorname = ? , email = ? , advisor = ? WHERE facilitatorid = ?");
	  $stmt->bind_param('sssi', $_POST['facilitatorname'], $_POST['email'], $_POST['advisor'], $_POST['id']);
	  $stmt->execute(); 
	  $stmt->close();
	} 

// DELETE A STUDENT
if(isset($_POST['delete'])) {
	$stmt = $db_server->prepare("DELETE FROM facilitators WHERE facilitatorid = ?");
	$stmt->bind_param('i', $_POST['id']);
	$stmt->execute(); 		
	$stmt->close();
	}
	
	

// GET THE LIST OF Students
	$result = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname");

?>

<form style="margin-bottom:1em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="newfacilitatorname" placeholder="Facilitator Name" required><br />
	<input type="text" name="newfacilitatoremail" placeholder="Facilitator Email"><br />
<select name='advisor'><option>Advisor?</option>
		<!--START OF GET OPTIONS AND QUERY STUFF-->
	        <?php $advisorget = $db_server->query("SELECT * FROM staff ORDER BY info ASC");
		      while ($advisor_option = $advisorget->fetch_assoc()) {
	        ?>  <option value= '<?php echo $advisor_option['info']; ?> '> <?php echo $advisor_option['info']; ?></option>
		<?php } ?></select><br />
	<input type="submit" name="addnew" value="Add Facilitator" />
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
      <th style="text-align:left">Advisor</th>
      <th style="text-align:left">Edit</th>
	  <th style="text-align:left">Delete</th>
   </tr>
<?php
// loop through list of names 
while ($list = mysqli_fetch_assoc($result)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['facilitatorid']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['facilitatorid'];
		if (isset($_POST[$editme])) { 
		?> 
		<td><input type="text" name="facilitatorname" class="textbox" value="<?php echo $list['facilitatorname']; ?>" required></td>
		<td><input type="text" name="email" class="textbox" value="<?php echo $list['email']; ?>"></td>
		
		
		
		
		
		<td><select name='advisor'><?php echo $list['advisor']; ?></option>
	        <?php
		     $staffget = $db_server->query("SELECT info FROM staff ORDER BY info ASC");
		     
		      while ($staff_option = $staffget->fetch_assoc()) {
			
			if (trim($list['info']) == $staff_option['advisor']){
			
			?><option selected value= '<?php echo $staff_option['advisor']; ?> '> <?php echo $staff_option['info']; ?></option><?php
			
			} else {
			
			?>  <option value= '<?php echo $staff_option['info']; ?> '> <?php echo $staff_option['info']; ?></option> <?php
			
			}
		      }
			?>
	        </select></td>
	        
		
		
		
		
		
		
		<td><button type="submit" name="save" value="<?php echo $list['facilitatorid']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $list['facilitatorname']; ?></td>
		<td><?php echo $list['email']; ?></td>
                <td><?php echo $list['advisor']; ?></td>
		
		<td><input type="submit" name="edit-<?php echo $list['facilitatorid']; ?>" value="Edit"></td>
		<?php } ?>	
		<td><button type="submit" name="delete" value="<?php echo $list['facilitatorname']; ?>">Delete</button></td>
	</tr>
</form>
<?php 
} // end while
?>
</table>	
</body>
</html>