<html>
        <head>
        <?php require_once('header.php'); ?>
    </head>
                <body style="background-color: dimgray;">
    <div id="puttheimagehere" style="position: fixed; opacity: 0.5; z-index: -1;">
	<img src="../img/mobius.png">
    </div>                    <div id="TopHeader">
                    <h1 class="Myheader">Update Facilitators</h1>
                    </div>
                    <div id="main">
<div class="facilitators">
<?php 
         // set up mysql connection
     $userlevel = "admin";
     require_once("../../login.php");
	 require_once("../../connection.php");
	 require_once("../../function.php");

// Making facilitator name look nice
if (isset($_POST['addFacilitatorTXT'])) {
$newFacilitatorName = $_POST['addFacilitatorTXT'];
$newFacilitatorName = ucfirst(strtolower($newFacilitatorName));
// Making facilitator email with name
$newFacilitatorEmail = $_POST['addFacilitatorTXT'];
$newFacilitatorEmail .= "@pscs.org";
$newFacilitatorEmail = strtolower($newFacilitatorEmail);
}
// Add Facilitator to Database
if (!empty($_POST['addFacilitatorTXT'])) {
    $stmt = $db_server->prepare("INSERT INTO facilitators (facilitatorname, email, advisor) VALUES (?, ?, ?)");
    $stmt->bind_param('ssi', $newFacilitatorName , $newFacilitatorEmail , $_POST['AdvisorDropDown']);
    $stmt->execute(); 
    $stmt->close();				
}

// Update facilitator
if (!empty($_POST['UpdateFac'])) {
    $stmt = $db_server->prepare("UPDATE facilitators SET facilitatorname = ? , email = ? , advisor = ? WHERE facilitatorid = ?");
    $stmt->bind_param('ssii', $_POST['UpdatedFacName'], $_POST['UpdatedFacEmail'], $_POST['UpdateAdvisorDropdown'], $_POST['id']);
    $stmt->execute(); 
    $stmt->close();
} 

// Remove Facilitator
if(!empty($_POST['DelFac'])) {
$stmt = $db_server->prepare("DELETE FROM facilitators WHERE facilitatorid = ?");
$stmt->bind_param('i', $_POST['id']);
$stmt->execute(); 		
$stmt->close();
}

// Query facilitator table
$FacResult = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname");

// Display added facilitator at top of page
if (!empty($_POST['addFacilitatorTXT'])) {
    echo "Added $newFacilitatorName as a facilitator!";
}
?>
    
<form action="?p=Facilitators" method="post">
    <input type="text" name="addFacilitatorTXT" placeholder="Facilitator Name">
    <select name="AdvisorDropDown">
  <option selected value="0">Advisor?</option>
  <option value="1">Yes</option>
  <option value="0">No</option>
</select>
        <input type="submit" name="addFacilitatorBTN" value="Add Facilitator">
    </form>
    
    <br />
    
    <table>
   <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Advisor</th>
      <th>Edit</th>
	  <th>Remove</th>
   </tr>
<?php
// Make list of all facilitators 
while ($FacList = mysqli_fetch_assoc($FacResult)) { ?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            <?php if ($FacList['advisor'] == 1) {
            $niceAdvisor = "Yes";
        } else {
           $niceAdvisor = "No"; 
        }
            ?>
<!-- This is for editing facilitators (How it knows what facilitator you close) -->
<input type="hidden" name="id" value="<?php echo $FacList['facilitatorid']; ?>">
    	<tr>
            
		<?php $editme = "EditFac-" . $FacList['facilitatorid'];
		if (!empty($_POST[$editme])) { 
		?> 
        <!-- Displays if you clicked the "Edit Button" -->
		<td><input type="text" name="UpdatedFacName" value="<?php echo $FacList['facilitatorname']; ?>" required></td>
		<td><input type="text" name="UpdatedFacEmail" value="<?php echo $FacList['email']; ?>"></td>
        <td>            <select name="UpdateAdvisorDropdown">
            <option selected value="0"><?php echo $niceAdvisor ?></option>
            <!-- Checking if facilitator is an avisor -->
            <?php if ($niceAdvisor == "No") {
            echo '<option value="1">Yes</option>';
            } else {
            echo '<option value="0">No</option>';
            } ?>
            </select>
            </td>
		<td><button type="submit" name="UpdateFac" value="<?php echo $FacList['FacID']; ?>">Update</button></td> 
		<?php } else { ?>
            
        <!-- Displays when you first load the page (Static Text) -->    
		<td><?php echo $FacList['facilitatorname']; ?></td>
		<td><?php echo $FacList['email']; ?></td>
        <td><?php echo $niceAdvisor ?></td>
		<td><input type="submit" name="EditFac-<?php echo $FacList['facilitatorid']; ?>" value="Edit"></td>
            
		<?php } ?>	
            
		<td><button type="submit" name="DelFac" value="<?php echo $FacList['facilitatorname']; ?>">Remove</button></td>
            
	</tr>
    
        </form>
        <?php } // Ends while loop for facilitator info ?>
    </table>
</div>
                    </div>
</body>
</html>