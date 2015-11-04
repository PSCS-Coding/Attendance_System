<?php
$admin = 1;
require_once('../../login.php');
?>
<html>
<head>
	<title>Edit Students</title>
	<?php require_once('header.php'); ?>
</head>
<body class="adminpage edit-students">
<!-- UPDATE FUNCTIONS -->     
<?php 
// Header Info
$HeaderStatus = null;
$HeaderInfo = "Update Students";
// ADD A NEW STUDENT			
if (isset($_POST['AddStudent'])) {
    if ($_POST['newAdvisor'] != "novalue") {
    $timestamp = strtotime($_POST['startdate']);
    $newStudentName = $_POST['newfirstname'];
    $stmt = $db_server->prepare("INSERT INTO studentdata (firstname, lastname, advisor, grade, startdate) VALUES (?, ?, ?, ?, FROM_UNIXTIME(?))");
    $stmt->bind_param('sssss', $_POST['newfirstname'] , $_POST['newlastname'] , $_POST['newAdvisor'] , $_POST['newGrade'] , $timestamp);
    $stmt->execute(); 
    $stmt->close();
    $HeaderStatus = "Sussess";
    $HeaderInfo = "Added $newStudentName as a student.";
    } else {
     $HeaderStatus = "Error";
     $HeaderInfo = "Please select a valid advisor.";
    }
    }

	
// EDIT (UPDATE) A STUDENT
if (isset($_POST['UpdateStudent'])) {
    $timestamp = strtotime($_POST['editstartdate']);
    $stmt = $db_server->prepare("UPDATE studentdata SET firstname = ? , lastname = ? , startdate = FROM_UNIXTIME(?) , advisor = ? , grade = ?, yearinschool = ? WHERE studentid = ?");
    $stmt->bind_param('sssssii', $_POST['firstname'], $_POST['lastname'], $timestamp, $_POST['selectedadvisor'], $_POST['gradeselect'], $_POST['yearinschool'], $_POST['StudentIDS']);
    $stmt->execute(); 
    $stmt->close();
} 

// DELETE A STUDENT
if(isset($_POST['deletestudent'])) {
    $stmt = $db_server->prepare("UPDATE studentdata SET current = 0 WHERE studentid = ?");
	$stmt->bind_param('i', $_POST['StudentIDS']);
	$stmt->execute();
	$stmt->close();
    $HeaderStatus = "Error";
    $HeaderInfo = "Deactivated Student.";
}

// Reactivate Student
if (isset($_POST['Reactivate'])) {
    $stmt = $db_server->prepare("UPDATE studentdata SET current = 1 WHERE studentid = ?");
    $stmt->bind_param('i', $_POST['DelStudentIDS']);
    $stmt->execute(); 
    $stmt->close();
    $HeaderStatus = "Sussess";
    $HeaderInfo = "Reactivated Student.";
} 
// Query for student list
	$StudentData = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname"); ?>
                                        <div id="TopHeader" class="<?php echo $HeaderStatus; ?>">
                    <h1 class="Myheader"><?php echo $HeaderInfo; ?></h1>
                    </div>
                    <div align="center" id="main">
<div class="admintable">
<form style="margin-bottom:1em;" action="" method="post">
	<input type="text" name="newfirstname" placeholder="First Name" required size="12">
	<input type="text" name="newlastname" placeholder="Last Name" required size="12">
	<input type="text" name="startdate" id="NewStartDate" placeholder="Start Date" required size="10"/>
    <select name='newAdvisor'>
            <option selected value="novalue">Advisor</option>
	        <?php // Query for advisor table
				 $GetFacilitators = $db_server->query("SELECT facilitatorname FROM facilitators WHERE advisor = 1 ORDER BY facilitatorname");
				 while ($FacList = $GetFacilitators->fetch_assoc()) { ?>  
				 <option value="<?php echo $FacList['facilitatorname']; ?>"><?php echo $FacList['facilitatorname']; ?></option>
				<?php } ?>
	</select>
	<select name='newGrade'>
		<option selected value="novalue">Grade</option>
		
		<option value="ms">MS</option>
		<option value="hs">HS</option>
	</select>
	<input type="submit" name="AddStudent" value="Add Student" />
</form>
<table class="students_table">
   <tr>
      <th>Name</th>
      <th>Enrolled</th>
      <th>Advisor</th>
	  <th>Grade</th>
      <th>Y</th>
	  <th>Modify</th>
   </tr>
<?php
// loop through list of names 
while ($StuDataList = mysqli_fetch_assoc($StudentData)) { ?>

<form action="" method="post">
<input type="hidden" name="StudentIDS" value="<?php echo $StuDataList['studentid']; ?>">
	<tr>
                <?php                                     
        $fName = $StuDataList['firstname'];
        $lName = $StuDataList['lastname'];
        $lastInitial = substr($StuDataList['lastname'], 0, 1);
        $fullName = $fName .' '. $lastInitial;
        ?>
		<?php $editme = "edit-" . $StuDataList['studentid'];
		if (isset($_POST[$editme])) { 
        ?> 
		<td><input type="text" size="10" name="firstname" class="textbox" value="<?php echo $fName; ?>" required>
        <input type="text" size="10" name="lastname" class="textbox" value="<?php echo $lName; ?>" required>
        </td>
		<td><input type="text" size="11" name="editstartdate" id="EStartDate" class="textbox" value="<?php echo $StuDataList['startdate']; ?>" required></td>
        		<td>
			<select name='selectedadvisor'>
	        <?php
                // Query for advisor table
				 $GetFacilitators = $db_server->query("SELECT facilitatorname FROM facilitators WHERE advisor = 1 ORDER BY facilitatorname");
            
				 while ($FacList = $GetFacilitators->fetch_assoc()) {
                     
					if ($StuDataList['advisor'] == $FacList['facilitatorname']) { ?>
                
				<option selected value="<?php echo $FacList['facilitatorname']; ?>"><?php echo $FacList['facilitatorname']; ?></option>
					<?php } else { ?>  
						<option value="<?php echo $FacList['facilitatorname']; ?>"><?php echo $FacList['facilitatorname']; ?></option>
				<?php
					}
			     }
			?>
	        </select>
		</td>
		
		<td>
			<select name='gradeselect'>
				<option value="ms">MS</option>
				<option value="hs">HS</option>
			</select>
		</td>		

		<td>
			<select name='yearinschool'>
	        <?php
				 $yearget = $db_server->query("SELECT yis FROM allottedhours ORDER BY yis ASC");
				 while ($year_option = $yearget->fetch_assoc()) {
					if ($StuDataList['yearinschool'] == $year_option['yis']) { ?>
						<option selected value="<?php echo $year_option['yis']; ?>"><?php echo $year_option['yis']; ?></option>
					<?php } else { ?>  
						<option value="<?php echo $year_option['yis']; ?>"> <?php echo $year_option['yis']; ?></option> 
				<?php
					}
			     }
			?>
	        </select>
		</td>
				
		<td>
        <button type="submit" name="UpdateStudent" value="<?php echo $StuDataList['studentid']; ?>">Update</button>
        <button type="submit" name="deletestudent" value="<?php echo $StuDataList['studentid']; ?>">Remove</button>
        </td>
		<?php } else { ?>
        
        <td><?php echo $fullName; ?></td>
        <?php
        $pretty_date = new DateTime($StuDataList['startdate']);                                                          
        ?>
		<td><?php echo $pretty_date->format('M, Y'); ?></td>
        <?php 
        $StuDataListAdvisor = $StuDataList['advisor'];
        if (empty($StuDataListAdvisor)) {
         $StuDataListAdvisor = "<font color='red'>Unknown</font>";
        }
        ?>
        <td><?php echo $StuDataListAdvisor; ?></td>
		<td><?php echo strtoupper($StuDataList['grade']); ?></td>
		<td><?php echo $StuDataList['yearinschool']; ?></td>
        <td><input type="submit" name="edit-<?php echo $StuDataList['studentid']; ?>" value="Edit">
            <button type="submit" name="deletestudent" value="<?php echo $StuDataList['studentid']; ?>">Remove</button>
        </td>
		<?php } ?>	
	</tr>
	</form>
<?php 
} // end while for student data
?>
</table>
<br /> 
<table class="students_table">
    <h1>Hidden Students</h1>
   <tr>
      <th>First</th>
      <th>Last</th>
      <th>Start Date</th>
      <th>Reactivate</th>
   </tr>
<?php

// Query to get all deleted students
	$DelStudentData = $db_server->query("SELECT * FROM studentdata WHERE current = 0 ORDER BY firstname ASC");
    
    while ($DelStuDataList = mysqli_fetch_assoc($DelStudentData)) { ?>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <input type="hidden" name="DelStudentIDS" value="<?php echo $DelStuDataList['studentid']; ?>">
            <tr>
        <td><?php echo $DelStuDataList['firstname']; ?></td>
		<td><?php echo $DelStuDataList['lastname']; ?></td>
		<td><?php echo $DelStuDataList['startdate']; ?></td>
        <td><button type="submit" name="Reactivate" value="<?php echo $DelStuDataList['studentid']; ?>">Reactivate</button></td>
                </tr>
        </form>
    <?php } ?>
    </table>
</div>
                    </div>
                      <!-- date picker javascript -->          
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('NewStartDate') });
</script>
                    <script>
    var picker = new Pikaday({ field: document.getElementById('EStartDate') });
</script>
		    
		    
<script>
       $(document).ready(function() {
	       $('#TopHeader').delay(1500);
	       setTimeout(function() {
		       $('#TopHeader').removeClass();
		       $('#TopHeader .MyHeader').text('Update Students');
	       }, 1700);
	       
       
       });
</script>
</body>
</html>