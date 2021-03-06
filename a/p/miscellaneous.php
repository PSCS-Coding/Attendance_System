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
<body class="miscellaneous">
<?php
// Header Info
$HeaderStatus = null;
$HeaderInfo = "Miscellaneous Settings";
// Add Facilitator to Database
if (!empty($_POST['addLocation'])) {
    $newLocation = $_POST['newLocation'];
    $stmt = $db_server->prepare("INSERT INTO offsiteloc (place) VALUES (?)");
    $stmt->bind_param('s', $newLocation);
    $stmt->execute(); 
    $stmt->close();	
    $HeaderStatus = "Sussess";
    $HeaderInfo = "Added $newLocation as an Offsite location.";
}

// Update facilitator
if (!empty($_POST['updateLocation'])) {
    $stmt = $db_server->prepare("UPDATE offsiteloc SET place = ? WHERE offsiteloc.id = ?");
    $stmt->bind_param('si', $_POST['updatedLocation'], $_POST['id']);
    $stmt->execute(); 
    $stmt->close();
    $HeaderStatus = "Success";
    $HeaderInfo = "Updated Offsite location.";
} 

// Remove Facilitator
if(!empty($_POST['deleteLocation'])) {
$stmt = $db_server->prepare("DELETE FROM offsiteloc WHERE offsiteloc.id = ?");
$stmt->bind_param('i', $_POST['id']);
$stmt->execute(); 		
$stmt->close();
$HeaderStatus = "Error";
$HeaderInfo = "Deleted Location.";
}

// Query offsite locations table
$offlocResult = $db_server->query("SELECT * FROM offsiteloc ORDER BY place ASC");
?>
<div id="TopHeader" class="<?php echo $HeaderStatus; ?>">
  <h1 class="Myheader"><?php echo $HeaderInfo; ?></h1>
   </div>
 <div align="center" id="main">
    <div class="admintable">
<form action="" method="post">
    <input type="text" name="newLocation" placeholder="World Pizza" size="15">
        <input type="submit" name="addLocation" value="Add">
    </form>
    
    <br />
    
    <table class="offloc_table">
   <tr>
      <th>Location</th>
       <th></th>
   </tr>
<?php
// Make list of all facilitators 
while ($offlocList = mysqli_fetch_assoc($offlocResult)) { ?>
<form action="" method="post">
<!-- This is for editing facilitators (How it knows what facilitator you close) -->
<input type="hidden" name="id" value="<?php echo $offlocList['id']; ?>">
    		<?php $editme = "EditOffloc-" . $offlocList['id'];
		if (!empty($_POST[$editme])) { 
		?>
    	<tr>
        <!-- Displays if you clicked the "Edit Button" -->
		<td><input type="text" name="updatedLocation" value="<?php echo $offlocList['place']; ?>" required size="15"></td>
<td><button type="submit" name="updateLocation" value="<?php echo $offlocList['id']; ?>">Update</button>
<button type="submit" name="deleteLocation" value="<?php echo $offlocList['place']; ?>">Delete</button></td>
            </tr>
		<?php } else { ?>
                    <!-- Displays when you first load the page (Static Text) -->    
    <td><?php echo $offlocList['place']; ?></td>
    <td>
        <input type="submit" name="EditOffloc-<?php echo $offlocList['id']; ?>" value="Edt">
        <button type="submit" name="deleteLocation" value="<?php echo $offlocList['place']; ?>">Delete</button></td>
            
                <?php } ?>
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
			$('#TopHeader .MyHeader').text('Miscellaneous Settings');
		}, 1700);
		
	
	});
 </script>
 
</body>
</html>