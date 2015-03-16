<?php
$admin = 1;
require_once('../../login.php');
?>
<html>
<head>
	<title>Edit Facilitators</title>
	<?php require_once('header.php'); ?>
	<script src="js/jquery.min.js"></script>
</head>
<body class="edit-facilitators">
<?php
// Header Info
$HeaderStatus = null;
$HeaderInfo = "Update Facilitators";
// Add Facilitator to Database
if (!empty($_POST['addFacilitatorTXT'])) {
    $newFacilitatorName = $_POST['addFacilitatorTXT'];
    $newFacilitatorEmail = $_POST['addFacilitatorEmail'];
    $stmt = $db_server->prepare("INSERT INTO facilitators (facilitatorname, email, advisor) VALUES (?, ?, ?)");
    $stmt->bind_param('ssi', $newFacilitatorName , $newFacilitatorEmail , $_POST['AdvisorDropDown']);
    $stmt->execute(); 
    $stmt->close();	
    $HeaderStatus = "Sussess";
    $HeaderInfo = "Added $newFacilitatorName as a facilitator.";
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
$HeaderStatus = "Error";
$HeaderInfo = "Deleted Facilitator.";
}

// Query facilitator table
$FacResult = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname");
?>
<div id="TopHeader" class="<?php echo $HeaderStatus; ?>">
  <h1 class="Myheader"><?php echo $HeaderInfo; ?></h1>
   </div>
 <div align="center" id="main">
    <div class="admintable">
<form action="" method="post">
    <input type="text" name="addFacilitatorTXT" placeholder="Facilitator Name" size="15">
    <input type="text" name="addFacilitatorEmail" placeholder="Email" size="12">
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
<form action="" method="post">
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
		<td><input type="text" name="UpdatedFacName" value="<?php echo $FacList['facilitatorname']; ?>" required size="15"></td>
		<td><input type="text" name="UpdatedFacEmail" value="<?php echo $FacList['email']; ?>" size="15"></td>
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
 
 <script>
	$(document).ready(function() {
		$('#TopHeader').delay(1500);
		setTimeout(function() {
			$('#TopHeader').removeClass();
			$('#TopHeader .MyHeader').text('Update Facilitators');
		}, 1700);
		
	
	});
 </script>
 
</body>
</html>