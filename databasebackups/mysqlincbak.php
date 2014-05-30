<?php
// Mysql Incremental Backup
// Version 2.1.2
// Author: Jeroen Houttuin, NetPresent.net / playak.com, Switzerland
// Contact: info@netpresent.net
// Copyright: All rights reserved. You may use this script for a small donation, but you are not allowed to sell, copy or change it, parts of it, or anything based on it.
// Price: Feel free to send a donation to paypal@playak.com if this script saves you time or money.
// Usage: I typically run this script from cron every so many hours or days, through the 'wget -q -T 3600 -O /dev/null http://mydomain.com/mysqlincbak.php' command
// History: I am using Amazon S3 from backups of my server, using the great tool 'duplicity', which does incremental
// backups. Since I couldn't find a good way to also include mysql backups, I wrote it myself.
// I hope it will help others too.

// config
// comma separated list of databases to backup
// e.g. $databases = "database1,database2";
$databases = "";
// database username. typically root if your want to backup more than one database (the user should obviously have access to all DBs)
$dbu = "";
// database password for the above user
$dbp = "";

// only change next lines if you don't want to use the default configuration
$backupdir = "mysqlincbak"; // make sure this relative directory exists and is writeable for the web server
$inifile = $backupdir."/last.ini";
$niceness = 18; // i typically keep this high (just below 20) to keep load on my server low
// I don't normally secure the script, as I don't even care if someone else runs the script on my behalf. The more backups the better :)
// If you want to secure it, edit (put your own servere's IP address in there, or you PC's IP address if you want to use this script
// manually) and uncomment the following line:
// $onlyip = "111.222.111.222";

// end of config
?>
<html>
<head>
<title>Mysql Incremental Backup</title>
</head>
<body>
<h1>Mysql Incremental Backup</h1>
<h2>&copy; <a href="http://www.netpresent.net" target="_blank">NetPresent</a></h2>
<p>If this backup script is saving you time or money, feel free to send me a donation of your choice :)<br>Thanks, Jeroen.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="9929895">
<input type="image" src="https://www.paypal.com/en_US/CH/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<p>[if your database contains large tables, this backup process may take a while to finish. please leave this page open in your browser until it is completely loaded.]</p>
<p>Start time: <?php echo date("Y-m-d H:i:s");?></p>
<?php
if ($onlyip && $_SERVER['REMOTE_ADDR'] != $onlyip)
	die("no access");
$databases = explode(",",$databases);
$absdir = dirname(__FILE__);
include_once("mysqlincbak.inc.php");
$starttime = microint();
$totalsize = 0;
$last = parse_ini_file($inifile, true);
if (!is_array($last))
	$last = array();
$dbc = mysql_connect("localhost",$dbu,$dbp);
foreach ($databases AS $database)
{
	$q = "SHOW TABLE STATUS FROM $database";
	$r = mysql_query($q);
    while($table = mysql_fetch_assoc($r))
    {
    	$tablename = $table['Name'];
    	$lastupdatetime = strtotime($table['Update_time']);
    	if ($lastupdatetime > $last[$database][$tablename] || !file_exists("$absdir/$backupdir/$database.$tablename.sql.gz")) // backup is old according to last.ini, or backup file got lost somehow
    	{
    		set_time_limit(10000);
    		$lasttime = microint();
    		$lastupdate = date("Y-m-d H:i:s",$lastupdatetime);
    		$prevupdate = date("Y-m-d H:i:s",$last[$database][$tablename]);
    		echo "Last table update: $lastupdate . Last backup: $prevupdate: <b>$database.$tablename</b>. Making new backup.";
    		$cmd = "nice -$niceness mysqldump -h localhost -u$dbu -p$dbp --add-drop-table $database $tablename > $absdir/$backupdir/$database.$tablename.sql"; exec($cmd);
    		$cmd = "nice -$niceness gzip -f $absdir/$backupdir/$database.$tablename.sql"; exec($cmd);
    		$last[$database][$tablename] = $lastupdatetime;
    		$now = microint();
    		$times["$database.$tablename"] = round($now-$lasttime,2);
    		$tablesize["$database.$tablename"] = $table['Data_length'];
    		$totalsize += $table['Data_length'];
    		echo " Execution time: ".round($now-$lasttime,2)." sec.<br>\n";
    		ob_flush();
		}
    }
}
if (!write_ini_file($last, $inifile,true))
	die("<br>ERROR could not write to inifile: $inifile");
if ($totalsize)
{
?>
<p>End time: <?php echo date("Y-m-d H:i:s");?></p>
<?php
// functions ///////////////////////////
	$endtime = microint();
	$extime= $endtime-$starttime;
	echo "<p><b>Total execution time</b>: ".round($extime,2)." sec. Total backup size (uncompressed): ".megabytes($totalsize)." MB.</p>";
	if (is_array($times))
	{
		arsort($times);
		echo "<table border='1'><tr><td colspan='3'><b>Backed up tables sorted by backup speed</b></td></tr>
		<tr><td align='right'><b>Seconds</b></td><td align='right'><b>MB (uncompressed)</b></td><td><b>Table</b></td></tr>";
		foreach ($times AS $table=>$time)
		{
			echo "<tr><td align='right'>".number_format($time,2)."</td><td align='right'>".megabytes($tablesize[$table])."</td><td>$table</td></tr>\n";
		}
		echo "</table>";
	}
?>
<p>If this backup script is saving you time or money, feel free to send me a donation of your choice :)<br>Thanks, Jeroen.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="9929895">
<input type="image" src="https://www.paypal.com/en_US/CH/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<?php
}
else
	echo "Nothing to be backed up.";
?>
</body>
</html>