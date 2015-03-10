<html>
 <head>
 <title>Edit Groups</title>
    <?php require_once('header.php'); ?>
 </head>
 <body>
 <?php
 require_once("../../connection.php");
 require_once("../../function.php");
  ?>

 <div class="groups">
 <?php
 if (!empty($_POST['addgroup'])) {
  $stmt = $db_server->prepare('INSERT INTO groups (name) VALUES (?)');
  $stmt->bind_param('s', $_POST['addgrouptext']);
  $stmt->execute();
  $stmt->close();
 }
 if (!empty($_POST['deletegroup'])) {
  $stmt = $db_server->prepare('DELETE FROM groups WHERE name=(?)');
  $stmt->bind_param('s', $_POST['deletegroup']);
  $stmt->execute();
  $stmt->close();
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
   array_push($editID, $_POST['studentselect']);
   $stringID = implode(',', $editID);
 /*var_dump($groupname);
 echo "<br />";
 var_dump($stringID);*/
  $stmt = $db_server->prepare('UPDATE groups SET studentid = ? WHERE name = ?');
  $stmt->bind_param('ss', $stringID, $groupname);
  $stmt->execute();
  $stmt->close();
 }
 $studentQuery = $db_server->query("SELECT studentid, firstname,lastname FROM studentdata ORDER BY firstname ASC");
    $studentResult = array();
    while ($student = $studentQuery->fetch_array()) {
     array_push($studentResult, $student);
     }
 $groupsQuery = $db_server->query("SELECT name, studentid, groupid FROM groups ORDER BY name DESC");
    $groupsResult = array();
    while ($group = $groupsQuery->fetch_array()) {
     array_push($groupsResult, $group);
     //$groupsCount += 1;
    }
 /*if (!empty($specGroupsResult)) {
 print_r($specGroupsResult);
 }*/
 ?>
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
   <option name='studentselect' value='<?php echo $student['studentid']; ?>'><?php echo $student['firstname']?><?php echo " "?><?php echo $lastinitial?></option>
   <?php
  }
  ?>
   </select>
   <!--<button type='submit' name='addstudent' value='<?php echo $student['studentid'];?>'>Add Student</button> -->
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
   ... <input type='submit' name='addstudent' value='Go'>
   </p>
  </form>
 <?php
  for ($i = 0; $i < count($groupsResult); $i++) {
   $ids = explode(",", $groupsResult[$i]['studentid']);
 if (!empty($_POST['deletestudent'])) {

  if (in_array($_POST['deletestudent'], $ids)) {
   //echo "You are deleting: " . idToName($_POST['deletestudent']) . "(" . $_POST['deletestudent'] . ") of the group: " . $groupsResult[$i]['name'];
   $key = array_search($_POST['deletestudent'], $ids);
   //echo $key;
   unset($ids[$key]);
   $idsString = implode(',', $ids);
   //echo "<br />Your new string: " . $ids;
   //print_r($idsString);

  $stmt = $db_server->prepare('UPDATE groups SET studentid = ? WHERE name = ?');
  $stmt->bind_param('ss', $idsString, $groupsResult[$i]['name']);
  $stmt->execute();
  $stmt->close();

    $groupsQuery = $db_server->query("SELECT name, studentid FROM groups ORDER BY name DESC");
    $groupsResult = array();
    while ($group = $groupsQuery->fetch_array()) {
     array_push($groupsResult, $group);
    }
  }

  /*echo $idsString;
  echo "<br />";
  echo $groupsResult[$i]['name'];
  */

 }

 //print_r($ids]);// . "<br />";
 //time for rendering add student
 //foreach($studentResult as $student) {
  //echo $student['studentid'] . "<br />";
 /*<?phpfor($y = 0; $y < count($studentResult); $y++) {
     echo "<option value=" . $studentResultdentResult[$y]['studentid'] . ">" . $studentResult[$y]['firstname'] . " " . $studentResult[$y]['lastname'][0] . "</option>";*/


 echo "
 <form method='post'>
 <table bgcolor='darkgrey'>
  <tr>
   <th>" . $groupsResult[$i]['name'] . "</th>
   <td><button style='font-weight:bold; border-radius:10px' type='submit' name='deletegroup' value='" . $groupsResult[$i]['name'] . "'>X</button></td>
  </tr>
  ";
 for($s = 0; $s < count($ids); $s++) {
  if (!empty($ids[$s])) {
 echo "<tr>
   <td>" . idToName($ids[$s]) . "</td>


   <td><button type='submit' name='deletestudent' value='" . $ids[$s] . "'>X</button></td>
  </tr>"; //edit is here >> <td><input type='submit' name='edit-" . $ids[$s] ."' value='Edit'></td>
  }
 }
 echo "
 </table>
 </form>
 ";
 }
 ?>
 </div>
 </body>
 </html>
