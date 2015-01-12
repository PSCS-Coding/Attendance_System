<DOCTYPE html>
<html>
<head>
<title>View Reports</title>
</head>
<body>
<?php
session_start();
	require("../connection.php");
		$studentlistquery = $db_server->query("
		SELECT firstname, lastname, studentid
		FROM studentdata
		WHERE current = 1
		ORDER BY firstname ASC
		")
		or die("Error querying database ".mysqli_error());
		
			if (empty($_SESSION['bettername'])){
				echo '<form method="post" action="viewreports.php">
				<select name="studentlist" class="studentlist">';
					while ($a_studentlist = $studentlistquery->fetch_assoc()){
								$studentoption = "<option class='name' value='" . $a_studentlist['studentid'] . "'>" . $a_studentlist['firstname'] . " " . $a_studentlist['lastname'][0] . "</option>";
								echo $studentoption;
								}
								echo '</select>
								<input type="submit" name="submit">
								</form>';
							
} else {
$name = $_SESSION['bettername'];
}


function completeCode() {

// connect to sql
require("../connection.php");
//require("function.php");


	//or die("Unable to select database: " . mysqli_error());
		if (!empty($_SESSION['bettername'])){
		$name = $_SESSION['bettername'];
		}
		if (!empty($_SESSION['name'])){
		$name = $_SESSION['name'];
		}
		if (!empty($_POST['studentlist'])){
        $name = $_POST['studentlist'];
		}
			$_SESSION['name'] = $name;
					$name = $_SESSION['name'];
		
					$totalindstudy = 0;
	//echo "<br/>".$bettername."<br/>".$name."<br/>";
		
		$studentdataquery = $db_server->query("
		SELECT studentid, firstname, lastname
		FROM studentdata
		WHERE studentid = '$name'
		")
		or die("Error querying database ".mysqli_error());
		
		while($studentdata = $studentdataquery->fetch_assoc()) {
		$bettername = $studentdata['firstname'];
		}
		$_SESSION['name1'] = $name;
		//$name just got turned from the name string the user put in into their ID integer string! that means from here on out $name means studentid!
	
		$querytwo = $db_server->query("
		SELECT elapsed
		FROM events
		WHERE studentid = '$name' 
		")
		or die("Error querying database ".mysqli_error());	
		$querythree = $db_server->query("
		SELECT offsitehours
		FROM allottedhours
		")
		or die("Error querying database ".mysqli_error());
		while ($hourdata = $querythree->fetch_assoc()) {
			$h_totaloffsite = $hourdata['offsitehours'];
			}
		
			$total = 0;
			$rowcount = mysqli_num_rows($querytwo);
		while ($elapseddata = $querytwo->fetch_assoc()) {
			
			
			foreach ($elapseddata as $item)  {
			$total = $total + $item;
			}
		}
		$style = '<p class="bettername">' . $bettername . '</p>
				<style>
				.bettername {
				font-size:20pt;
				}
				* {
				font-family:Arial;
				}
				</style>';
				echo $style;
				if (!empty($_POST['submit'])) {
	echo '
	<b>
	<form method="post"><input type="hidden" value="' . $name . '" name="hidden">
	<p>How many minutes per day could I spend offsite if I want to have
	<select name="hours">
	<option value="5">5</option>
	<option value="10">10</option>
	<option value="20">20</option>
	<option value="30">30</option>
	<option value="40">40</option>
	<option value="50">50</option>
	</select>
	 hours of offsite left at the end of the year?<input type="submit" name="go" value="Go"></p>
	</form>
	</b>';
	}
	
	
			//$total is how many minutes of offsite you've spent
				$m_totaloffsite = $h_totaloffsite * 60;
			//$m_totaloffsite is how many minutes of offsite you have total
				$m_offsiteleft = $m_totaloffsite - $total;
			//$m_offsiteleft is how many minutes of offsite you have left to spend
				$roundedminutes = 60 * floor($m_offsiteleft / 60);
				$h_offsiteleft = $roundedminutes / 60;
			//$h_offsiteleft is how many hours off offsite you have left to spend
				$formattedoffsiteleft = $m_offsiteleft - $roundedminutes;
				$readable_offsiteleft = "You have " . $h_offsiteleft . " hours and " . $formattedoffsiteleft . " minutes of offsite left.";
					if ($h_offsiteleft < 0) {
				$readable_offsiteleft = "You are out of offsite!";
				}
				if ($rowcount < 0){
				$m_averagetimeoutdecimal = $total / $rowcount; } else { $m_averagetimeoutdecimal = 0; }
				$m_averagetimeout = floor($m_averagetimeoutdecimal);
				$readable_averagetimeout = "You spend an average of " . $m_averagetimeout . " minutes away when you go offsite.";
				
				
					// down is independant study calculations
						$indstudyquery = $db_server->query("
							SELECT timestamp, elapsed
							FROM events
							WHERE studentid = '$name'
							AND statusid = 6
							") or die("Error querying database ".mysqli_error());
						$yearquery = $db_server->query("
							SELECT yearinschool
							FROM studentdata
							WHERE studentid = '$name'
							") or die("Error querying database ".mysqli_error());
							while ($a_yearinschool = $yearquery->fetch_assoc()) {
							$yearinschool = $a_yearinschool['yearinschool'];
							}
						$allotedisquery = $db_server->query("
							SELECT IShours
							FROM allottedhours
							WHERE yis = '$yearinschool'
							") or die("Error querying database ".mysqli_error($db_server));
							
							while ($a_is = $allotedisquery->fetch_assoc()){
								$totalis = $a_is['IShours'];
								}
							$totalis = $totalis * 60;
							while ($a_indstudy = $indstudyquery->fetch_assoc()) {
								if (empty($totalindstudy)){
								$totalindstudy = $a_indstudy['elapsed'];
								} else {
								$totalindstudy = $totalindstudy + $a_indstudy['elapsed'];
								}
								}
								$readable_totalindstudy = 'You have used ' . $totalindstudy . ' minutes of Independant Study.';
								$indstudyavailable = $totalis - $totalindstudy;
								$roundedis = floor($indstudyavailable / 60) * 60;
								$m_ind = $indstudyavailable - $roundedis;
								$roundedis = $roundedis / 60;
								$readable_indstudyleft = 'You have ' . $roundedis . ' hours and ' . $m_ind . ' minutes available of Independant Study.';
			
				$w1 = 0;$w2 = 0;$w3 = 0;$w4 = 0;$w5 = 0;$w6 = 0;$w7 = 0;$w8 = 0;
				$w9 = 0;$w10 = 0;$w11 = 0;$w12 = 0;$w13 = 0;$w14 = 0;$w15 = 0;$w16 = 0;$w17 = 0;$w18 = 0;
				$w19 = 0;$w20 = 0;$w21 = 0;$w22 = 0;$w23 = 0;$w24 = 0;$w25 = 0;$w26 = 0;
				$w27 = 0;$w28 = 0;$w29 = 0;$w30 = 0;$w31 = 0;$w32 = 0;$w33 = 0;$w34 = 0;$w35 = 0;
				$w36 = 0;$w37 = 0;$w38 = 0;$w39 = 0;$w40 = 0;$w41 = 0;$w42 = 0;$w43 = 0;
				$w44 = 0;$w45 = 0;$w46 = 0;$w47 = 0;$w48 = 0;$w49 = 0;$w50 = 0;$w51 = 0; $w52 = 0;
					$timestampquery = $db_server->query("
							SELECT timestamp, elapsed
							FROM events
							WHERE studentid = '$name'
							AND statusid = 1
							") or die("Error querying database ".mysqli_error());
					
						while ($q_timestamp = $timestampquery->fetch_assoc()) {
							$s_timestamp = strtotime($q_timestamp['timestamp']);			//$s_timestamp is unix timestamp format of the timestamp of the event
							$w_timestamp = date('W', strtotime($q_timestamp['timestamp']));	//$w_timestamp is the week format of the timestamp of the event
							$y_timestamp = date('Y', strtotime($q_timestamp['timestamp'])); //$y_timestamp is the numeric year of the timestamp of the event
							
				if ($w_timestamp == 1) {$w1 = $q_timestamp['elapsed'] + $w1;}if ($w_timestamp == 2) {$w2 = $q_timestamp['elapsed'] + $w2;}
				if ($w_timestamp == 3) {$w3 = $q_timestamp['elapsed'] + $w3;}if ($w_timestamp == 4) {$w4 = $q_timestamp['elapsed'] + $w4;}
				if ($w_timestamp == 5) {$w5 = $q_timestamp['elapsed'] + $w5;}if ($w_timestamp == 6) {$w6 = $q_timestamp['elapsed'] + $w6;}
				if ($w_timestamp == 7) {$w7 = $q_timestamp['elapsed'] + $w7;}if ($w_timestamp == 8) {$w8 = $q_timestamp['elapsed'] + $w8;}
				if ($w_timestamp == 9) {$w9 = $q_timestamp['elapsed'] + $w9;}if ($w_timestamp == 10) {$w10 = $q_timestamp['elapsed'] + $w10;}
				if ($w_timestamp == 11) {$w11 = $q_timestamp['elapsed'] + $w11;}if ($w_timestamp == 12) {$w12 = $q_timestamp['elapsed'] + $w12;}
				if ($w_timestamp == 13) {$w13 = $q_timestamp['elapsed'] + $w13;}if ($w_timestamp == 14) {$w14 = $q_timestamp['elapsed'] + $w14;}
				if ($w_timestamp == 15) {$w15 = $q_timestamp['elapsed'] + $w15;}if ($w_timestamp == 16) {$w16 = $q_timestamp['elapsed'] + $w16;}
				if ($w_timestamp == 17) {$w17 = $q_timestamp['elapsed'] + $w17;}if ($w_timestamp == 18) {$w18 = $q_timestamp['elapsed'] + $w18;}
				if ($w_timestamp == 19) {$w19 = $q_timestamp['elapsed'] + $w19;}if ($w_timestamp == 20) {$w20 = $q_timestamp['elapsed'] + $w20;}
				if ($w_timestamp == 21) {$w21 = $q_timestamp['elapsed'] + $w21;}if ($w_timestamp == 22) {$w22 = $q_timestamp['elapsed'] + $w22;}
				if ($w_timestamp == 23) {$w23 = $q_timestamp['elapsed'] + $w23;}if ($w_timestamp == 24) {$w24 = $q_timestamp['elapsed'] + $w24;}
				if ($w_timestamp == 25) {$w25 = $q_timestamp['elapsed'] + $w25;}if ($w_timestamp == 26) {$w26 = $q_timestamp['elapsed'] + $w26;}
				if ($w_timestamp == 27) {$w27 = $q_timestamp['elapsed'] + $w27;}if ($w_timestamp == 28) {$w28 = $q_timestamp['elapsed'] + $w28;}
				if ($w_timestamp == 29) {$w29 = $q_timestamp['elapsed'] + $w29;}
				if ($w_timestamp == 30) {$w30 = $q_timestamp['elapsed'] + $w30;}if ($w_timestamp == 31) {$w31 = $q_timestamp['elapsed'] + $w31;}
				if ($w_timestamp == 32) {$w32 = $q_timestamp['elapsed'] + $w32;}if ($w_timestamp == 33) {$w33 = $q_timestamp['elapsed'] + $w33;}
				if ($w_timestamp == 34) {$w34 = $q_timestamp['elapsed'] + $w34;}if ($w_timestamp == 35) {$w35 = $q_timestamp['elapsed'] + $w35;}
				if ($w_timestamp == 36) {$w36 = $q_timestamp['elapsed'] + $w36;}if ($w_timestamp == 37) {$w37 = $q_timestamp['elapsed'] + $w37;}
				if ($w_timestamp == 38) {$w38 = $q_timestamp['elapsed'] + $w38;}if ($w_timestamp == 39) {$w39 = $q_timestamp['elapsed'] + $w39;}
				if ($w_timestamp == 40) {$w40 = $q_timestamp['elapsed'] + $w40;}if ($w_timestamp == 41) {$w41 = $q_timestamp['elapsed'] + $w41;}
				if ($w_timestamp == 42) {$w42 = $q_timestamp['elapsed'] + $w42;}if ($w_timestamp == 43) {$w43 = $q_timestamp['elapsed'] + $w43;}
				if ($w_timestamp == 44) {$w44 = $q_timestamp['elapsed'] + $w44;}if ($w_timestamp == 45) {$w45 = $q_timestamp['elapsed'] + $w45;}
				if ($w_timestamp == 46) {$w46 = $q_timestamp['elapsed'] + $w46;}if ($w_timestamp == 47) {$w47 = $q_timestamp['elapsed'] + $w47;}
				if ($w_timestamp == 48) {$w48 = $q_timestamp['elapsed'] + $w48;}
				if ($w_timestamp == 49) {$w49 = $q_timestamp['elapsed'] + $w49;}if ($w_timestamp == 50) {$w50 = $q_timestamp['elapsed'] + $w50;}
				if ($w_timestamp == 51) {$w51 = $q_timestamp['elapsed'] + $w51;}if ($w_timestamp == 52) {$w52 = $q_timestamp['elapsed'] + $w52;}
										
								}
					//echoing week offsite		echo $w1;echo '<br />';echo $w2;echo '<br />';echo $w3;echo '<br />';echo $w4;echo '<br />';echo $w5;echo '<br />';	echo $w6;echo '<br />';echo $w7;echo '<br />';echo $w8;echo '<br />';echo $w9;echo '<br />';echo $w10;echo '<br />';echo $w11;echo '<br />';echo $w12;echo '<br />';echo $w13;echo '<br />';echo $w14;echo '<br />';echo $w15;echo '<br />';	echo $w16;echo '<br />';echo $w17;echo '<br />';echo $w18;echo '<br />';echo $w19;echo '<br />';echo $w20;echo '<br />';echo $w21;echo '<br />';echo $w22;echo '<br />';echo $w23;echo '<br />';echo $w24;echo '<br />';echo $w25;echo '<br />';echo $w26;echo '<br />';echo $w27;echo '<br />';echo $w28;echo '<br />';echo $w29;echo '<br />';echo $w30;echo '<br />';	echo $w31;echo '<br />';echo $w32;echo '<br />';echo $w33;echo '<br />';echo $w34;echo '<br />';echo $w35;echo '<br />';	echo $w36;echo '<br />';echo $w37;echo '<br />';echo $w38;echo '<br />';echo $w39;echo '<br />';echo $w40;echo '<br />';	echo $w41;echo '<br />';echo $w42;echo '<br />';echo $w43;echo '<br />';echo $w44;echo '<br />';echo $w45;echo '<br />';	echo $w46;echo '<br />';echo $w47;echo '<br />';echo $w48;echo '<br />';echo $w49;echo '<br />';echo $w50;echo '<br />';	echo $w51;echo '<br />';echo $w52;echo '<br />';
						
					$daysleft = 50;
							//$daysleft is me hardcoding in how many days there are left in the school year, we have to add in a query that pulls the amount of days left from a table or something.
							if (!empty($_POST['hours'])){
						$userhours = $_POST['hours'];
						$m_userhours = $userhours * 60;
						$m_availableoffsite = $m_totaloffsite - $m_userhours;
						$newavailableoffsite = $m_availableoffsite / $daysleft;
						$newavailableoffsite = floor($newavailableoffsite);
						$readable_offsiteif = 'If you want to have ' . $userhours . ' hours of offsite at the end of the year, you can spend ' . $newavailableoffsite . ' minutes a day offsite.';
						echo '<b>';
						
						echo '<form method="post"><p>How many minutes per day could I spend offsite if I want to have ' . $_POST['hours'] . ' hours of offsite left at the end of the year?<input type="submit" name="submit4" value="submit4"></p>';
					
						echo $readable_offsiteif;
						echo '</b>';
						echo '<br />';
						echo '<br />';
						}
						if (!empty($_POST['submit4'])){
						completeCode();
						}
						
								echo $readable_offsiteleft;			echo '<br />';
								echo $readable_averagetimeout;		echo '<br /><br />';
								echo $readable_totalindstudy;		echo '<br />';
								echo $readable_indstudyleft;        echo '<br /><br />';
								
								
							
								$w1 = ceil($w1 / 10) * 10 / 10 + 4;$w2 = ceil($w2 / 10) * 10 / 10 + 4;$w3 = ceil($w3 / 10) * 10 / 10 + 4;$w4 = ceil($w4 / 10) * 10 / 10 + 4;$w5 = ceil($w5 / 10) * 10 / 10 + 4;$w6 = ceil($w6 / 10) * 10 / 10 + 4;$w7 = ceil($w7 / 10) * 10 / 10 + 4;$w8 = ceil($w8 / 10) * 10 / 10 + 4;$w9 = ceil($w9 / 10) * 10 / 10 + 4;$w10 = ceil($w10 / 10) * 10 / 10 + 4;$w11 = ceil($w11 / 10) * 10 / 10 + 4;$w12 = ceil($w12 / 10) * 10 / 10 + 4;$w13 = ceil($w13 / 10) * 10 / 10 + 4;
								$w14 = ceil($w14 / 10) * 10 / 10 + 4;$w15 = ceil($w15 / 10) * 10 / 10 + 4;$w16 = ceil($w16 / 10) * 10 / 10 + 4;$w17 = ceil($w17 / 10) * 10 / 10 + 4;$w18 = ceil($w18 / 10) * 10 / 10 + 4;$w19 = ceil($w19 / 10) * 10 / 10 + 4;$w20 = ceil($w20 / 10) * 10 / 10 + 4;$w21 = ceil($w21 / 10) * 10 / 10 + 4;$w22 = ceil($w22 / 10) * 10 / 10 + 4;$w23 = ceil($w23 / 10) * 10 / 10 + 4;$w24 = ceil($w24 / 10) * 10 / 10 + 4;$w25 = ceil($w25 / 10) * 10 / 10 + 4;$w26 = ceil($w26 / 10) * 10 / 10 + 4;
								$w27 = ceil($w27 / 10) * 10 / 10 + 4;$w28 = ceil($w28 / 10) * 10 / 10 + 4;$w29 = ceil($w29 / 10) * 10 / 10 + 4;$w30 = ceil($w30 / 10) * 10 / 10 + 4;$w31 = ceil($w31 / 10) * 10 / 10 + 4;$w32 = ceil($w32 / 10) * 10 / 10 + 4;$w33 = ceil($w33 / 10) * 10 / 10 + 4;$w34 = ceil($w34 / 10) * 10 / 10 + 4;$w35 = ceil($w35 / 10) * 10 / 10 + 4;$w36 = ceil($w36 / 10) * 10 / 10 + 4;$w37 = ceil($w37 / 10) * 10 / 10 + 4;$w38 = ceil($w38 / 10) * 10 / 10 + 4;
								$w39 = ceil($w39 / 10) * 10 / 10 + 4;$w40 = ceil($w40 / 10) * 10 / 10 + 4;$w41 = ceil($w41 / 10) * 10 / 10 + 4;$w42 = ceil($w42 / 10) * 10 / 10 + 4;$w43 = ceil($w43 / 10) * 10 / 10 + 4;$w44 = ceil($w44 / 10) * 10 / 10 + 4;$w45 = ceil($w45 / 10) * 10 / 10 + 4;$w46 = ceil($w46 / 10) * 10 / 10 + 4;$w47 = ceil($w47 / 10) * 10 / 10 + 4;$w48 = ceil($w48 / 10) * 10 / 10 + 4;$w49 = ceil($w49 / 10) * 10 / 10 + 4;$w50 = ceil($w50 / 10) * 10 / 10 + 4;
								$w51 = ceil($w51 / 10) * 10 / 10 + 4;$w52 = ceil($w52 / 10) * 10 / 10 + 4;
						//	 echo '<br />' . $w14 . '<br />';
								$highestoffsite = max($w1, $w2, $w3, $w4, $w5, $w6, $w7, $w8, $w9, $w10, $w11, $w12, $w13, $w14, $w15, $w16, $w17, $w18, $w19, $w20, $w21, $w22, $w23, $w24, $w25, $w26, $w27, $w28, $w29, $w30, $w31, $w32, $w33, $w34, $w35, $w36, $w37, $w38, $w39, $w40, $w41, $w42, $w43, $w44, $w45, $w46, $w47, $w48, $w49, $w50, $w52);
						//		echo '<br />' . $highestoffsite . '<br />';
							 echo '<div class="bars">';
								echo '<div class="w1">.</div>';echo '<div class="w2">.</div>';echo '<div class="w3">.</div>';echo '<div class="w4">.</div>';echo '<div class="w5">.</div>';
								echo '<div class="w6">.</div>';echo '<div class="w7">.</div>';echo '<div class="w8">.</div>';echo '<div class="w9">.</div>';echo '<div class="w10">.</div>';
								echo '<div class="w11">.</div>';echo '<div class="w12">.</div>';echo '<div class="w13">.</div>';echo '<div class="w14">.</div>';echo '<div class="w15">.</div>';
								echo '<div class="w16">.</div>';echo '<div class="w17">.</div>';echo '<div class="w18">.</div>';echo '<div class="w19">.</div>';echo '<div class="w20">.</div>';
								echo '<div class="w21">.</div>';echo '<div class="w22">.</div>';echo '<div class="w23">.</div>';echo '<div class="w24">.</div>';echo '<div class="w25">.</div>';	
								echo '<div class="w26">.</div>';echo '<div class="w27">.</div>';echo '<div class="w28">.</div>';echo '<div class="w29">.</div>';echo '<div class="w30">.</div>';
								echo '<div class="w31">.</div>';echo '<div class="w32">.</div>';echo '<div class="w33">.</div>';echo '<div class="w34">.</div>';echo '<div class="w35">.</div>';
								echo '<div class="w36">.</div>';echo '<div class="w37">.</div>';echo '<div class="w38">.</div>';echo '<div class="w39">.</div>';echo '<div class="w40">.</div>';
								echo '<div class="w41">.</div>';echo '<div class="w42">.</div>';echo '<div class="w43">.</div>';echo '<div class="w44">.</div>';echo '<div class="w45">.</div>';
								echo '<div class="w46">.</div>';echo '<div class="w47">.</div>';echo '<div class="w48">.</div>';echo '<div class="w49">.</div>';echo '<div class="w50">.</div>';
								echo '<div class="w51">.</div>';echo '<div class="w52">.</div>';
									echo '</div>';
								
								echo '<style>
								div {
								border-radius:3px;
								}
							

								.w1 {height: ' . $w1 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w2 {height: ' . $w2 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w3 {height: ' . $w3 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w4 {height: ' . $w4 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w5 {height: ' . $w5 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w6 {height: ' . $w6 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w7 {height: ' . $w7 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w8 {height: ' . $w8 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w9 {height: ' . $w9 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w10 {height: ' . $w10 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w11 {height: ' . $w11 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w12 {height: ' . $w12 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w13 {height: ' . $w13 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w14 {height: ' . $w14 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w15 {height: ' . $w15 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w16 {height: ' . $w16 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w17 {height: ' . $w17 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w18 {height: ' . $w18 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w19 {height: ' . $w19 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w20 {height: ' . $w20 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w21 {height: ' . $w21 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w22 {height: ' . $w22 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w23 {height: ' . $w23 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w24 {height: ' . $w24 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w25 {height: ' . $w25 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w26 {height: ' . $w26 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w27 {height: ' . $w27 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w28 {height: ' . $w28 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w29 {height: ' . $w29 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w30 {height: ' . $w30 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w31 {height: ' . $w31 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w32 {height: ' . $w32 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w33 {height: ' . $w33 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w34 {height: ' . $w34 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w35 {height: ' . $w35 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w36 {height: ' . $w36 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w37 {height: ' . $w37 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w38 {height: ' . $w38 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w39 {height: ' . $w39 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w40 {height: ' . $w40 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w41 {height: ' . $w41 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w42 {height: ' . $w42 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w43 {height: ' . $w43 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w44 {height: ' . $w44 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w45 {height: ' . $w45 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w46 {height: ' . $w46 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w47 {height: ' . $w47 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w48 {height: ' . $w48 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
								.w49 {height: ' . $w49 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w50 {height: ' . $w50 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w51 {height: ' . $w51 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}.w52 {height: ' . $w52 . 'px;width: 10px;font-size:0pt;background-color:red;vertical-align: bottom;margin-left: 4px;display: inline-block;}
									  </style>';
						//			  echo '<br /><table cellspacing="0" class="graphtable" style="400px">';
									
						// DO FOR LOOP FOR ECHOING TR		for {
						//		for  ($i=0;$i<$highestoffsite;$i++) {
						//		echo '<tr><td>lal</td><td>lol</td><td>lil</td></tr>';
						//		}
						//	echo '</table>';
						//			echo '<br /><table style="200px"><tr><td>ll</td><td>ll</td><td>ll</td></tr><tr><td>ll</td><td>ll</td><td>ll</td></tr><tr><td>ll</td><td>ll</td><td>ll</td></tr></table>';
						
						
					$_SESSION['readable_offsiteleft'] = $readable_offsiteleft;
					$_SESSION['readable_averagetimeout'] = $readable_averagetimeout;
					
								
								//
								echo '<form method="post">
										<select name="calendar">
											<p>Log</p>
											<option value="0">All</option>
											<option value="2">Offsite</option>
											<option value="3">Field Trip</option>
											<option value="6">Independant Study</option>
										</select>
									<input type="submit" name="submit3" value="submit3">
						  </form>';
								//
							
									 $style1 = '<style>
								input[value="submit3"] {
			background:url(http://code.pscs.org/attendance/down.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			input[value="Go"] {
			background:url(http://code.pscs.org/attendance/down.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			input[value="submit4"] {
			background:url(http://code.pscs.org/attendance/up.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			.logtable {
			border-style:solid;
			border-width:2px;
			}
			.logtable th {
			border-style:solid;
			border-width:2px;
			}
			.logtable tr {
			border-style:solid;
			border-width:2px;
			}
			.logtable td {
			border-style:solid;
			border-width:2px;
		
			}
			.graphtable table{
			border-width:1px;
			}
			.graphtable th {
			border-style:solid;
			border-width:thin;
			margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable td {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable tr {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
				border-spacing:0px;
			margin-right:0px;
			border-collapse:collapse;
			}
			
		</style>
	';
		echo $style1;
			$_SESSION['style'] = $style;
			$_SESSION['style1'] = $style1;
		
						
			if (!empty($_POST['submit3'])){
					
				$name = $_SESSION['name'];
					$bettername = $_SESSION['name1'];
						$calendarinput = $_POST['calendar'];
							if ($calendarinput == 0){
							$eventsquery = $db_server->query("
								SELECT timestamp, elapsed, info, returntime, statusid, studentid
								FROM events
								WHERE studentid = '$name'
								AND statusid = 2 OR statusid = 3 OR statusid =
								ORDER BY timestamp DESC
								")
								or die("Error querying database ".mysqli_error());
								} else {
								$eventsquery = $db_server->query("
								SELECT timestamp, elapsed, info, returntime, statusid, studentid
								FROM events
								WHERE studentid = '$name'
								AND statusid = '$calendarinput'
								ORDER BY timestamp DESC
								")
								or die("Error querying database ".mysqli_error());
								}
								
												echo '<table class="logtable" style="width:1000px"><tr>';
											if ($calendarinput == 0){
										echo '<th>Type</th>';
									}
								echo	'
										<th>Name</th>
										<th>Time</th>
										<th>Return Time</th>
										<th>Date</th>
										<th>Days Ago</th>
										<th>Elapsed</th>
										<th>Where</th>
										</th>
										';
								
							while ($eventsarray = $eventsquery->fetch_assoc()){
								$strtotimetimestamp = strtotime($eventsarray['timestamp']);
									$totalsec = $strtotimetimestamp + $eventsarray['elapsed'] * 60;
										$daysago = date('z') - date('z', strtotime($eventsarray['timestamp']));
											$early_late = 'earlier';
												$unixtime_returntime = strtotime($eventsarray['returntime']);
												
																		
														if ($eventsarray['elapsed'] > 0) {
													$smallerthanzero = 0;
												if ($eventsarray['elapsed'] < 0){
													$smallerthanzero = 1;
													}
													if ($calendarinput == 0) {
												if ($eventsarray['statusid'] > 3){
													$smallerthanzero = 1;
													}
													}
													if ($smallerthanzero == 0){
													echo '<tr>';
												$statusid = $eventsarray['statusid'];
											if ($calendarinput == 0){
								if ($eventsarray['statusid'] == 1){
									$eventsarray['statusid'] = 'Present';
									}
								if ($eventsarray['statusid'] == 2){
									$eventsarray['statusid'] = 'Offsite';
									}
								if ($eventsarray['statusid'] == 3){
									$eventsarray['statusid'] = 'Field Trip';
									}
									
									echo '<td>' . $eventsarray['statusid'] . '</td>';
									}
																
																$elapsedsec = $eventsarray['elapsed'] * 60;
															
														
																$unixtime_realtime = $strtotimetimestamp + $elapsedsec;
															
															
															$difference = $unixtime_returntime - $unixtime_realtime;
															$difference = ceil($difference / 60);
														
													
															//strtotimetimestamp is unix time version of when you got back (works)
															if ($difference < 0){
															$difference = $difference * -1;
															$early_late = 'later';
															}
														
																
															
								echo '
									<td>' . $eventsarray['studentid'] . '</td>
									<td><p>' . date('g:i A', strtotime($eventsarray['timestamp'])) . '  to ' . date('g:i A', $totalsec) . '</p></td>
									<td><p>You said you would be back by ' . date('g:i', strtotime($eventsarray['returntime'])) . '. You came back at ' .  date('g:i A', $totalsec) . '</p></td>
									<td>' . date('F j, o', strtotime($eventsarray['timestamp'])) . '</td>
									<td><p>' . $daysago . ' Days Ago</p></td>';
									if ($eventsarray['elapsed'] < 60){
									$elapsed_x = 'You were gone for ' . $eventsarray['elapsed'] . ' minutes.';
									echo '
									<td>' . $elapsed_x . '</td>';
									}
									
									$elapsed_m = $eventsarray['elapsed'];
									
								if ($elapsed_m > 60){
										$elapsed_m = $eventsarray['elapsed'];
								$elapsed_m = $elapsed_m - 60;
								if ($elapsed_m > 60) {
								$elapsed_m = $elapsed_m - 60;
								$elapsed = 'You were gone for 2 hours and ' . $elapsed_m . ' minutes.';
								echo '<td>' . $elapsed . '</td>';
								} else {
								$elapsed = 'You were gone for 1 hour and ' . $elapsed_m . ' minutes.';
									echo '<td>' . $elapsed . '</td>';
									}
									}
									if ($statusid == 2){
										echo '
										<td><p>You went to ' . $eventsarray['info'] . '</p></td>';
										}
									if ($statusid == 3){
										echo '
										<td><p>You went with ' . $eventsarray['info'] . '</p></td>';
										}
									if ($statusid == 1){
										echo '
										<td><p>Present</p></td>';
										}
													echo '</tr>';}}}
															
													
								echo '</table>';
								echo '<br />';
								}
							}
						
				//TO DO
				
			//$daysleft is me hardcoding in how many days there are left in the school year, we have to add in a query that pulls the amount of days left from a table or something.
			//SEE LINE 120
														
														if (!empty($_POST['submit3'])){
														$bettername = $_SESSION['name'];
														$name = $_SESSION['name1'];
										completeCode();
								}
														
														if (!empty($_POST['submit4'])){
																$name = $_SESSION['name1'];
																echo $_SESSION['style'];
																echo $_SESSION['style1'];
																echo '
												<b>
												<form method="post"><input type="hidden" value="' . $name . '" name="hidden">
												<p>How many minutes per day could I spend offsite if I want to have
													<select name="hours" border-radius="6px">
														<option value="5">5</option>
														<option value="10">10</option>
														<option value="20">20</option>
														<option value="30">30</option>
														<option value="40">40</option>
														<option value="50">50</option>
													</select>
												hours of offsite left at the end of the year?<input type="submit" name="go" value="Go"></p>
												</form>
												</b>';
																echo $_SESSION['readable_offsiteleft'];
																echo '<br />';
																echo $_SESSION['readable_averagetimeout'];
																echo '<br />';
																echo '<form method="post">
									<input type="text" name="selector3">
									<input type="submit" name="submit3" value="submit3">
									  </form>';
										echo '<br />';
																}
						
														if (!empty($_POST['submit'])) {
																completeCode();
																}
														if (!empty($_SESSION['bettername'])){
																completeCode();
																
																}
														if (!empty($_POST['go']) && !empty($_POST['hidden'])) {
																completeCode();
																}
		?>
			<style>
			.logtable {
			border-style:solid;
			border-width:2px;
			}
			.logtable th {
			border-style:solid;
			border-width:2px;
			}
			.logtable tr {
			border-style:solid;
			border-width:2px;
			}
			.logtable td {
			border-style:solid;
			border-width:2px;
		
			}
			.graphtable table{
			border-width:1px;
			}
			.graphtable th {
			border-style:solid;
			border-width:thin;
			margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable td {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable tr {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
				border-spacing:0px;
			margin-right:0px;
			border-collapse:collapse;
			}
			</style>
		</body>
		</html>