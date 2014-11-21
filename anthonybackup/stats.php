<html>
	<head>
		<title>Statistics</title>
	</head>
<body>
<?php
session_start();

require_once("connection.php");
require_once("function.php");

$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
 
//make this $_SESSION['adminSet'] if it's an admin-only page
if(!$_SESSION['set']) {
	header("location: main_login.php");
	}

	$getNamesQuery = $db_server->query("
										SELECT info FROM events
										WHERE statusid = 2
										");
		$placesList = array();
	//	$placesCount = array();
	while($getNamesArray = $getNamesQuery->fetch_assoc()) {
		if (!in_array($getNamesArray['info'], $placesList)) {
			array_push($placesList, $getNamesArray['info'])	;
			}
		}
		
		/*  if (empty($placesList[$getNamesArray['info']])) {
				$placesList[$getNamesArray['info']] += 1;
					} else {
				$placesList[$getNamesArray['info']] += 1;
			}
		*/
		//print_r ($placesList);
		foreach ($placesList as $sub) {
			$count = 0;
			echo $sub . ": ";
			$tempQuery = $db_server->query("SELECT info FROM events WHERE info = $sub");
			/*while($tempArray = $tempQuery->fetch_assoc()) {
				$count += 1;
				}
			*/
			print_r($tempQuery);
			echo $count . "<br />";
			}
	/*	
		$countArray = array();
		//print_r(array_count_values($placesList));
		$countArray = array_count_values($placesList);
		print_r($countArray);
		echo "<br />";
		$totalPlaces = array_sum($countArray);
		echo $totalPlaces;
		echo "<br /><br/>";
	*/
		
		/*foreach ($countArray as $sub) {
			echo $countArray	 . ': '; 
			echo $sub . '<br />';
			}
		*/
    
   // next($array);
//d}
		//print_r($placesList);
		
	//foreach($totalPlaces as $sub) {
		
	//$placesString = "";
		//	foreach($placesList as $sub) {
		//		$placesString = $placesString . " " . $sub;
		//		}
	//		echo $placesString;
	//	echo "<br />";
	//	print_r($placesCount);
?>
</body>
</html>