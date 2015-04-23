<?php
date_default_timezone_set('America/Los_Angeles');
//changestatus inserts name, status and any comment associated into the studentInfo database
function changestatus($f_id, $f_status, $f_info, $f_returntime)
{
    global $db_server;
    $result = $db_server->query("SELECT timestamp FROM events WHERE studentid='$f_id' ORDER BY timestamp DESC LIMIT 1");
    $rowdata = $result->fetch_array(MYSQLI_BOTH);
    $f_info = strip_tags($f_info);
    $last = new DateTime($rowdata['timestamp']);
    $now = new DateTime();
    $lastdate = $last->format('Y-m-d');
    $last330 = $lastdate . '15:30:00';
    $lastendofday = new DateTime($last330);
    $nowstamp = $now->getTimestamp();
    $laststamp = $last->getTimestamp();
    $lastendstamp = $lastendofday->getTimestamp();
    if ($nowstamp > $lastendstamp) {
        $minutes = round(($lastendstamp - $laststamp)/60);
    } else {
        $minutes = round(($nowstamp - $laststamp)/60);
    }

    $whenreturn = new DateTime($f_returntime);
    $returntimestring = $whenreturn->format('Y-m-d H:i:s');
    $stmt = $db_server->prepare("INSERT INTO events (studentid, statusid, info, returntime) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $f_id, $f_status, $f_info, $returntimestring);
    $stmt->execute();
    $stmt->close();
}
//defines valid time entries for time text boxes
//only allows integers and colons
function validTime($inTime)
{
    //$pattern   =   "/^(((([9])|([0-2])|([0-1][0-5])):([0-5][0-9]))|(([3]):(([0-2][0-9])|([3][0]))))$/";
    //if (preg_match($pattern, $inTime)) {
        return true;
    //}
}
//checks if you've hit any of the submit buttons that are a part of the top form
function isPost()
{
    if (in_array("Present", $_POST)) {
        return true;
    } elseif (in_array("Offsite", $_POST)) {
        return true;
    } elseif (in_array("Field Trip", $_POST)) {
        return true;
    } elseif (in_array("Check Out", $_POST)) {
        return true;
    } else {
        return false;
    }
}
//function add favorite
function favorite($id, $status, $info, $returntime)
{
    global $db_server;
    if (!empty($returntime)) {
        $whenreturn = new DateTime($returntime);
        $returntimestring = $whenreturn->format('H:i:s');
    } else {
        $returntimestring="";
    }
    $info = strip_tags($info);
    $getfav = $db_server->query("SELECT * FROM cookiedata WHERE studentid = '".$id."'");
    $frowcnt =  $getfav->num_rows;

    if ($frowcnt <10) {
        $stmt=$db_server->prepare("INSERT INTO cookiedata (studentid, statusid, info, returntime) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $id, $status, $info, $returntimestring);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "There is a maximum of ten favorites";
    }
}

// ========== valid date function =======
function validDate($v_date)
{
    require_once("connection.php");
    global $db_server;
    $globalsQuery = $db_server->query("SELECT *
                                  FROM globals
                                  ");

    $holidayQuery = $db_server->query("SELECT *
                                  FROM holidays
                                  ");
                                  
    while ($globalsArray = $globalsQuery->fetch_assoc()) {
        $startDate = $globalsArray['startdate'];
        $endDate = $globalsArray['enddate'];
        $startTime = $globalsArray['starttime'];
        $endTime = $globalsArray['endtime'];
    }

    $currentDate = new DateTime($startDate);
    $endDate = new DateTime($endDate);
    $dateList = array();

    while ($currentDate <= $endDate) {
        $weekday = $currentDate->format("w");
        if ($weekday != 0 && $weekday != 6) {
            $currentDateString = $currentDate->format("Y-m-d");
    
            array_push($dateList, $currentDateString);
        }
        date_add($currentDate, date_interval_create_from_date_string('1 day'));
    }
    
    // remove holidays
    $rowcnt =  $holidayQuery->num_rows;
    while ($rowcnt>0) {
        $holidayRow = mysqli_fetch_row($holidayQuery);
        $currentHoliday = $holidayRow[2];
        if (in_array($currentHoliday, $dateList)) {
            $key = array_search($currentHoliday, $dateList);
            unset($dateList[$key]);
        }
        $rowcnt = $rowcnt-1;
    }
    if (!in_array($v_date, $dateList)) {
        return false;
    } else {
        return true;
    }
}

//function plan
function plan($id, $status, $eventdate, $returntime, $info, $endeventdate)
{
	$dayDiff = 1;
    global $db_server;
    $info = strip_tags($info);
	$startDate = DateTime::createFromFormat( 'U', $eventdate );

	if($endeventdate != null){
		$endDate = new DateTime($endeventdate);
		$dayDiff = $endDate->diff($startDate)->format("%a");
		$dayDiff = $dayDiff + 1;
	}
	
    if (!empty($returntime)) {
        $whenreturn = new DateTime($returntime);
        $returntimestring = $whenreturn->format('H:i:s');
    } else {
        $returntimestring="";
    }
        
	if (empty($endDate)){
		$endDate = $startDate;
	}
	
	$eventDateObject = new DateTime();
	
	while ($dayDiff != 0){

		date_timestamp_set($eventDateObject, $eventdate);
		$eventDateString = $eventDateObject->format('Y-m-d');
		
	if(validDate($eventDateString)){
    $stmt = $db_server->prepare(
        "INSERT INTO preplannedevents
        (studentid, statusid, eventdate, returntime, info)
        VALUES (?, ?, FROM_UNIXTIME(?), ?, ?)"
    );
    
    $stmt->bind_param('iisss', $id, $status, $eventdate, $returntimestring, $info);
    $stmt->execute();
    $stmt->close();
	} else {
		?>
		<div class='error'><?php echo $eventDateObject->format('l, M j, Y') ?> is not a school day</div>
		
		<?php
	}
	if ($endDate < $startDate){
			$eventdate = $eventdate - 24*60*60;
	} else {
		$eventdate = $eventdate + 24*60*60;
	}
	$dayDiff--;
	}
}

function login()
{
    global $login;
    if (isset($_SESSION['student'])) {
        return true;
        $login="student";
    } elseif (isset($_SESSION['admin'])) {
        return true;
        $login="admin";
    }
}

function sendmail($facilitator, $message)
{
    $headers = 'From: PSCS Attendance' . "\r\n" .
    'Reply-To: code.pscs.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    mail($facilitator, "PSCS Attendance", $message, $headers);
}

// ========= num school days ===========
function daysLeft()
{
    require_once("connection.php");
    global $db_server;
    $globalsQuery = $db_server->query("SELECT *
                                  FROM globals
                                  ");

    $holidayQuery = $db_server->query("SELECT *
                                  FROM holidays
                                  ");
                                  
    while ($globalsArray = $globalsQuery->fetch_assoc()) {
        $startDate = $globalsArray['startdate'];
        $endDate = $globalsArray['enddate'];
        $startTime = $globalsArray['starttime'];
        $endTime = $globalsArray['endtime'];
    }

    $startDate = new DateTime($startDate);
    $currentDate = new DateTime();
    $endDate = new DateTime($endDate);
    $dateList = array();

    while ($currentDate <= $endDate) {
        $weekday = $currentDate->format("w");
        if ($weekday != 0 && $weekday != 6) {
            $currentDateString = $currentDate->format("Y-m-d");
    
            array_push($dateList, $currentDateString);
        }
        date_add($currentDate, date_interval_create_from_date_string('1 day'));
    }
    
    // remove holidays
    $rowcnt =  $holidayQuery->num_rows;
    while ($rowcnt>0) {
        $holidayRow = mysqli_fetch_row($holidayQuery);
        $currentHoliday = $holidayRow[2];
        if (in_array($currentHoliday, $dateList)) {
            $key = array_search($currentHoliday, $dateList);
            unset($dateList[$key]);
        }
        $rowcnt = $rowcnt-1;
    }
        $returnInfo = count($dateList);
        return($returnInfo);
}

// ========= num school days ===========
function daysLeftFromDate($start)
{
    require_once("connection.php");
    global $db_server;
    $globalsQuery = $db_server->query("SELECT *
                                  FROM globals
                                  ");

    $holidayQuery = $db_server->query("SELECT *
                                  FROM holidays
                                  ");
                                  
    while ($globalsArray = $globalsQuery->fetch_assoc()) {
        $startDate = $globalsArray['startdate'];
        $endDate = $globalsArray['enddate'];
        $startTime = $globalsArray['starttime'];
        $endTime = $globalsArray['endtime'];
    }

    $startDate = new DateTime($startDate);
    $currentDate = new DateTime($start);
    $endDate = new DateTime($endDate);
    $dateList = array();

    while ($currentDate <= $endDate) {
        $weekday = $currentDate->format("w");
        if ($weekday != 0 && $weekday != 6) {
            $currentDateString = $currentDate->format("Y-m-d");
    
            array_push($dateList, $currentDateString);
        }
        date_add($currentDate, date_interval_create_from_date_string('1 day'));
    }
    
    // remove holidays
    $rowcnt =  $holidayQuery->num_rows;
    while ($rowcnt>0) {
        $holidayRow = mysqli_fetch_row($holidayQuery);
        $currentHoliday = $holidayRow[2];
        if (in_array($currentHoliday, $dateList)) {
            $key = array_search($currentHoliday, $dateList);
            unset($dateList[$key]);
        }
        $rowcnt = $rowcnt-1;
    }
        $returnInfo = count($dateList);
        return($returnInfo);
}

function idToName($id)
{
    global $db_server;
    $query = $db_server->query("SELECT firstname FROM studentdata WHERE studentid = $id");
    $tempvar = $query->fetch_assoc();
    $name = $tempvar['firstname'];
    return($name);
}
function idToLastName($id)
{
    global $db_server;
    $query = $db_server->query("SELECT lastname FROM studentdata WHERE studentid = $id");
    $tempvar = $query->fetch_assoc();
    $lastname = $tempvar['lastname'];
    return($lastname);
}

function statconvert($id)
{
    global $db_server;
    $query = $db_server->query("SELECT statusname FROM statusdata WHERE statusid = $id");
    $tempvar = $query->fetch_assoc();
    $name = $tempvar['statusname'];
    return($name);
}

function groupIdToName($id)
{
    global $db_server;
    $query = $db_server->query("SELECT name FROM groups WHERE groupid = $id");
    $tempvar = $query->fetch_assoc();
    $name = $tempvar['name'];
    return($name);
}
function groupNameToId($name)
{
    global $db_server;
    $query = $db_server->query("SELECT groupid FROM groups WHERE name = $name");
    $tempvar = $query->fetch_assoc();
    $id = $tempvar['groupid'];
    return($id);
}

function calculateStats($current_student_id) //======================== passes out an array of view-reports type information ====================================
{
	
global $db_server;

if (isset($current_student_id)) {
	//holidays array
$holiday_data_array = array();
$holiday_dt_array = array();
$holidayquery = "SELECT date FROM holidays";
$holiday_result = $db_server->query($holidayquery);

while ($holiday_data = $holiday_result->fetch_array()) {
	array_push($holiday_dt_array, $holiday_data);
}
foreach ($holiday_dt_array as $k) {
	array_push($holiday_data_array, $k[0]);
}
$student_data_array = array();
//fetches most recent data from the events table
//joins with the tables that key student names/status names to the ids in the events table
$result = $db_server->query("SELECT info,statusname,studentdata.studentid,studentdata.firstname,timestamp,returntime,events.eventid, yearinschool
		FROM events
		JOIN statusdata ON events.statusid = statusdata.statusid
		RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
		WHERE studentdata.studentid = $current_student_id
		ORDER BY timestamp ASC") or die(mysqli_error($db_server));
while ($student_data_result = $result->fetch_assoc()) {
	array_push($student_data_array, $student_data_result);
}

//allotted hours query
$yearinschool = $student_data_array[0]['yearinschool'];
$allottedquery = "SELECT * FROM allottedhours WHERE yis = '$yearinschool'";
$allotted_result = $db_server->query($allottedquery);
$allotted_data_array = $allotted_result->fetch_array();

//globals query
$globalsquery = "SELECT * FROM globals";
$globals_result = $db_server->query($globalsquery);
$globalsdata = $globals_result->fetch_array();

$starttime = $globalsdata['starttime'];
$endtime = $globalsdata['endtime'];
$startdate = $globalsdata['startdate'];
$lastdate = $globalsdata['enddate'];

$study_all = $allotted_data_array['IShours'] * 60;
$offsite_all = $allotted_data_array['offsitehours'] * 60;
$commhours_all = $allotted_data_array['communityhours'] * 60;

$studyhours_remaining = $study_all;
$offsitehours_remaining = $offsite_all;
$commhours_remaining = $commhours_all;

$query = $db_server->query("SELECT yearinschool FROM studentdata WHERE studentid = $current_student_id");
$tempvar = $query->fetch_assoc();
$studentYis = $tempvar['yearinschool'];

$query = $db_server->query("SELECT startdate FROM studentdata WHERE studentid = $current_student_id");
$tempvar = $query->fetch_assoc();
$studentStartDate = $tempvar['startdate'];

$startDateStamp = new DateTime($studentStartDate);
$yearDateStamp = new DateTime($startdate);

if ($startDateStamp > $yearDateStamp){ // if a student is mid-year, then nerf the number of IS and offsite hours they have
$remainingDays = daysLeftFromDate($studentStartDate);
$totalDays = daysLeftFromDate($startdate);

$baseHours = $remainingDays / $totalDays;
$baseISHours = $remainingDays / $totalDays;
$totalISHours = $study_all / 60;
$totalHours = $offsite_all / 60;
$baseHours = $baseHours * $totalHours;
$baseISHours = $baseISHours * $totalISHours;

$offsitehours_used = 0;
$commhours_used = -$baseHours * 60;
$studyhours_used = 0;
$studyhours_remaining = $study_all - $baseISHours * 60;
$offsitehours_remaining = $offsite_all - $baseHours * 60;
$commhours_remaining = $commhours_all - $baseHours * 60;

} else { // if not a new student, do nothing
	$offsitehours_used = 0;
	$commhours_used = 0;
	$studyhours_used = 0;
	$studyhours_remaining = $study_all;
	$offsitehours_remaining = $offsite_all;
	$commhours_remaining = $commhours_all;
}

$num_lates = 0;
$num_unexpected = 0;
$num_absent = 0;

$offsiteremaining = $offsitehours_remaining / 60;
//counts time
//loops through each event for the given student
foreach($student_data_array as $event_key => $event_val) {
	if ($student_data_array[$event_key]['statusname'] != "Not Checked In") {
	if (count($student_data_array) != $event_key) {
		//makes the timestamp into datetime obj
		if (isset($student_data_array[$event_key+1])) {
			$event_datetime_2 = new DateTime($student_data_array[$event_key+1]['timestamp']);
		}
		else {
			break;
		}
		$event_datetime_1 = new DateTime($event_val['timestamp']);
		//variables for easy comparison
		$early = $event_datetime_1->format('Y-m-d') . " " . $starttime;
		$late = $event_datetime_1->format('Y-m-d') . " " . $endtime;
		$event_early = new DateTime($early);
		$event_late = new DateTime($late);
		//is event 1 on a weekend or holiday?
		if (($event_datetime_1->format('w') == 0 || $event_datetime_1->format('w') == 6) || (in_array($event_datetime_1->format('Y-m-d'), $holiday_data_array) == True)) {
			continue;
		}
		//is event 1 before 9:00am?
		if ($event_datetime_1 <= $event_early){
			$event_datetime_1 = clone $event_early;
		}
		//is event 2 after 3:30 of the same day of event 1?
		if ($event_datetime_2 >= $event_late){
			$event_datetime_2 = clone $event_late;
		}
		
		if ($event_datetime_2 > $event_datetime_1) { // without this logic, you can end up with (adjusted) event 1 coming *after* event 2!  (For example, a "checked out" event at 3:45 will result in a 15 minute diff.)
			$elapsed = $event_datetime_2->diff($event_datetime_1);
		} else {
			$elapsed = $event_datetime_1->diff($event_datetime_1);
		}
		//format as total minutes
		$elapsed_minutes = ($elapsed->format('%h')*60) + ($elapsed->format('%i'));
		
		if ($event_val['statusname'] == 'Present' || $event_val['statusname'] == 'Field Trip' || $event_val['statusname'] == 'Independent Study') {
			$commhours_remaining -= $elapsed_minutes;
			$commhours_used += $elapsed_minutes;
			if ($event_val['statusname'] == 'Independent Study') {
				$studyhours_remaining -= $elapsed_minutes;
				$studyhours_used += $elapsed_minutes;
			}
		}
		else {
			$offsitehours_remaining -= $elapsed_minutes;
			$offsitehours_used += $elapsed_minutes;
			if (($event_val['statusname'] == 'Not Checked In') && ($event_datetime_1 >= new DateTime($starttime))) {
				$num_unexpected += 1;
				$num_lates += 1;
			}
			if ($event_val['statusname'] == 'Late') {
				$num_lates += 1;
			}
			if ($event_val['statusname'] == 'Absent') {
				$num_absent += 1;
				}
			}
		}
	}
}
$offsiteHrs_remaining = floor($offsitehours_remaining / 60);
$offsiteMin_remaining = $offsitehours_remaining % 60;

$objstarttime = new DateTime($starttime);
$objendtime = new DateTime($endtime);
$daydiff = $objstarttime->diff($objendtime);
$minutesinday = $daydiff->format('%i');
$hoursinday = $daydiff->format('%h');
$hoursinday = $hoursinday * 60;
$totalminsinday = $hoursinday + $minutesinday;
$totalminsinday = $totalminsinday / 60;

$fulldaysleft = floor($offsitehours_remaining / $minutesinday);

$returnArray = array();
array_push($returnArray,$offsiteHrs_remaining,$offsiteMin_remaining);


$offsiteHrs_used = floor(($offsitehours_used) / 60);
$offsiteMin_used = $offsitehours_used % 60;

// this uses a function from functions.php
$daystillend = daysLeft();

if ($daystillend > 0) {
$minutesperday = floor($offsitehours_remaining / $daystillend);
array_push($returnArray,$minutesperday);
} else {
array_push($returnArray,null);
}

$daysInYear = daysLeftFromDate($globalsdata['startdate']);

$yearPercent = floor(100 - ($daystillend / $daysInYear * 100));

$offsitePercent = floor($offsiteHrs_used / $offsiteremaining * 100);

array_push($returnArray,$offsitePercent);
array_push($returnArray,$yearPercent);

return $returnArray;	
} else {
	return null;
}

}

function convertHours($whichfield)
{
    
    if (!empty($_POST[$whichfield])) {
    // Make post a DateTime
        $newEndTime = new DateTime($_POST[$whichfield]);
        // Temp start time
        $astartTime = new DateTime('09:00:00');
        // If start time is greater that POST add 12 hrs
        if ($newEndTime <= $astartTime) {
            // Add 12 hrs to POST
            $newEndTime->add(new DateInterval('PT12H'));
            // Formating code
            $sqlEndTime = $newEndTime->format('Y-m-d H:i:s');
            return $sqlEndTime;
    
        } else {
            
        return $_POST[$whichfield];
    }
        
    }
    
}

?>
