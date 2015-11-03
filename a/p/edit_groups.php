<?php
$admin = 1;
require_once('../../login.php');
?>
<html>
 <head>
 <title>Edit Groups</title>
	    <?php require_once('header.php'); ?>
 </head>
<body class="adminpage edit-groups">
 <?php

// Header Info
    $HeaderStatus = null;
    $HeaderInfo = "Update Groups";

if (!empty($_POST['addgroup'])) {
	$_POST['addgrouptext'] = str_replace(" ","_", $_POST['addgrouptext']);
        $stmt = $db_server->prepare('INSERT INTO groups (name) VALUES (?)');
        $stmt->bind_param('s', $_POST['addgrouptext']);
        $stmt->execute();
        $stmt->close();
        $NewGroup = $_POST['addgrouptext'];
        $HeaderStatus = "Sussess";
        $HeaderInfo = "Added group $NewGroup.";
 }

 if (!empty($_POST['deletegroup'])) {
        $stmt = $db_server->prepare('DELETE FROM groups WHERE name=(?)');
        $stmt->bind_param('s', $_POST['deletegroup']);
        $stmt->execute();
        $stmt->close();
        $HeaderStatus = "Error";
        $HeaderInfo = "Deleted Group.";
 }

 if (!empty($_POST['addstudent'])) {
        $groupname = $_POST['groupselect'];
        $specGroupsQuery = $db_server->query("SELECT name,studentid FROM groups WHERE name = '$groupname' ORDER BY name ASC");
        $specGroupsResult = array();
        while ($specGroup = $specGroupsQuery->fetch_assoc()) {
        array_push($specGroupsResult, $specGroup);
}

     
        // $_POST['studentselect']; IS A STUDENT ID
        $editID = explode(',', $specGroupsResult[0]['studentid']);
	if (!in_array($_POST['studentselect'], $editID)) {
        	array_push($editID, $_POST['studentselect']);
        $stringID = implode(',', $editID);
     
        $stmt = $db_server->prepare('UPDATE groups SET studentid = ? WHERE name = ?');
        $stmt->bind_param('ss', $stringID, $groupname);
        $stmt->execute();
        $stmt->close();
        $HeaderStatus = "Sussess";
        $HeaderInfo = "Added Student " . idToName($_POST['studentselect']);
		} else {
	$HeaderStatus = "Error";
	$HeaderInfo = "Student Already in Group " . $groupname;
	}
 }
if (!empty($_POST['deletestudent'])) {
	$infoArray = explode("-", $_POST['deletestudent']);
	$groupSelected = $infoArray[0];
	$studentSelected = $infoArray[1];
	//echo $groupSelected . '<br />' . $studentSelected;

	$getGroupSelected = mysqli_fetch_assoc(mysqli_query($db_server, "SELECT * FROM groups WHERE name = '" . $groupSelected . "' ORDER BY name DESC LIMIT 1"));
	$idsSelected = explode(",", $getGroupSelected['studentid']);
	if (in_array($studentSelected, $idsSelected)) {

	$key = array_search($studentSelected, $idsSelected);
    	unset($idsSelected[$key]);

	$idsSelectedString = implode(',', $idsSelected);
	//echo "<br />" . $idsSelectedString . "<br />";
        $stmt = $db_server->prepare('UPDATE groups SET studentid = ? WHERE name = ?');
        $stmt->bind_param('ss', $idsSelectedString, $groupSelected);
        $stmt->execute();
        $stmt->close();
	$HeaderStatus = "Error";
        $HeaderInfo = "Deleted Student " . idToName($studentSelected);
	}
}
 $studentQuery = $db_server->query("SELECT studentid, firstname,lastname FROM studentdata WHERE current = 1 ORDER BY firstname ASC");
        $studentResult = array();
        while ($student = $studentQuery->fetch_array()) {
        array_push($studentResult, $student);
}

 $groupsQuery = $db_server->query("SELECT name, studentid, groupid FROM groups ORDER BY name DESC");
        $groupsResult = array();
        while ($group = $groupsQuery->fetch_array()) {
        array_push($groupsResult, $group);
    }

 ?>
    
<div id="TopHeader" class="<?php echo $HeaderStatus; ?>">
    <h1 class="Myheader"><?php echo $HeaderInfo; ?></h1>
</div>
    
<div align="center" id="main" class="admintable">
    
    <form method='post'>
        <p style='font-style:italic'>
        <input type='text' name='addgrouptext' placeholder='Group Name'>
        <input type='submit' name='addgroup' value='Add Group'>
        </p>
    </form>
    
 <form method='post'>
    <p style='font-style:italic'>
        
    Add
        
    <select name='studentselect' class='studentselect'>
        <?php
        foreach($studentResult as $student) {
        echo $student['studentid'];
        $lastinitial = substr($student['lastname'], 0, 1); ?>
        ?>
        <option name='studentselect' value='<?php echo $student['studentid']; ?>'><?php echo $student['firstname']?><?php echo " "?><?php           echo $lastinitial?></option>
        <?php
        }
        ?>
    </select>
        
  to group
        
    <select name='groupselect' class='groupselect'>
        <?php
        foreach($groupsResult as $group) {
        ?>
        <option name='groupselect' value='<?php echo $group['name']; ?>'><?php echo $group['name']?></option>
        <?php
        }
        ?>
    </select>
        
    <input type='submit' name='addstudent' value='Go'>
        
    </p>
 </form>
    
<?php
for ($i = 0; $i < count($groupsResult); $i++) {
    
    $ids = explode(",", $groupsResult[$i]['studentid']);

    echo "
    <form method='post' style='display:inline'>
    <table bgcolor='darkgrey' class='GroupsTable'>
    <tr>
    <th class='groupheader'>" . str_replace("_"," ", $groupsResult[$i]["name"]) . "</th>
    <td><button style='font-weight:bold; border-radius:10px' type='submit' name='deletegroup' value='" . $groupsResult[$i]['name'] .             "'>X</button></td>
    </tr>
    ";
    
    for($s = 0; $s < count($ids); $s++) {
        
        if (!empty($ids[$s])) {
        echo "<tr>
        <td>" . idToName($ids[$s]) . " " . idToLastName($ids[$s])[0] . "</td>

        <td><button type='submit' name='deletestudent' value='" . $groupsResult[$i]['name'] . "-" . $ids[$s] . "'>X</button></td>
        </tr>"; //edit is here >> <td><input type='submit' name='edit-" . $ids[$s] ."' value='Edit'></td>
        }
    }
    
    echo " </table> </form> ";
 }
 ?>
    </div>
     <script>
	$(document).ready(function() {
		$('#TopHeader').delay(1500);
		setTimeout(function() {
			$('#TopHeader').removeClass();
			$('#TopHeader .MyHeader').text('Update Groups');
		}, 1700);
		
	
	});
 </script>
 </body>
 </html>
<style>

.groupnames {
	width:115px;
}
.groupheader {
	width:115px;
}
</style>