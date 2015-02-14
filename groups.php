<?php
session_start();

require_once("connection.php");
require_once("function.php");

 ?><html>
<head>
	<title>Groups</title>
</head>
<body>
<?php	
$groupsQuery = $db_server->query("SELECT name, studentids FROM groups ORDER BY name DESC");
$groupsResult = array();
while ($group = $groupsQuery->fetch_array()) {
	array_push($groupsResult, $group);
}
echo "<form method='post'>";	
for ($j = 0; $j < count($groupsResult); $j++) {
	echo "<input type='submit' name='submit' value='" . $groupsResult[$j]["name"] . "'>";
	echo "<br />";
	}
echo "</form>";
print_r($groupsResult);
echo "<br /><br /><br />";
$ids = explode(",", $groupsResult[0]['studentids']);
//echo $ids . "<br / >";
for ($i = 0; $i < count($ids); $i++) {
echo $ids[$i];
//echo "-";
}
?><form method='post'>
        <input type='checkbox' name='checkbox' id='69' class='69'>
    </form>
  
<script>
    document.getElementById(69).checked = true;
</script>
