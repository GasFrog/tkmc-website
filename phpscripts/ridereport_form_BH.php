<?php

if (!defined('SCRIPTS_DIR')) {
	define('SCRIPTS_DIR', '/home1/templark/scripts');
}
require_once(SCRIPTS_DIR.'/includes/defines.inc');
require_once(SCRIPTS_DIR.'/classes/Database.class');

//Connect To Database
$db = LocalDatabase::getReadable('master');
$ridetable='ride_directory';
$knighttable='knight_directory';
$corollarytable='ride_corollary';

$query = 'SELECT * FROM ' . $ridetable . ' ORDER BY DATE DESC';
echo '<div style="height:709px;width:742px;overflow-x:hidden;overflow-y:scroll;"><table>';

$result = mysql_query($query, $db->connection);
if($result) {
	while($row = mysql_fetch_array($result)){
		$knightArray = "";
		echo '<tr><td width="300"><font size="4"><b>' . $row['NAME'] . '</b></font></td><td width="300">' . $row['DATE'] . '&nbsp;&nbsp;&nbsp;&nbsp;Miles: ' . $row['MILES'] . '&nbsp;&nbsp;&nbsp;&nbsp;Hours: ' . $row['HOURS'] . '</td></tr>' ;
		$isCharity='';
		if ($row['IS_CHARITY']) {
			$isCharity = '*Charity Ride*';
		}
		if(substr($row['ROUTE'],0,4) == "http") {
			$route = '<a href="' . $row['ROUTE'] . '" target="blank">Route Map</a>';
		} else {
			$route = $row['ROUTE'];
		}
		echo '<tr><td></td><td><br>Route: ' .  $route . '<br><br>Summary:   ' . $isCharity . '<br>' . $row['RIDE_SUMMARY'] . '<br><br>Participants: <br>';
		$cquery = 'SELECT * FROM ' . $corollarytable . ' WHERE RIDE_NUM = ' . $row['RIDE_NUM'] . ' ORDER BY KNIGHT_NUM';
		$cresult = mysql_query($cquery, $db->connection);
		if($cresult) {
			while($crow = mysql_fetch_array($cresult)) {
				$riderquery = 'SELECT * FROM ' . $knighttable . ' WHERE RECORD_NUM = ' . $crow['KNIGHT_NUM'];
				$riderresult = mysql_query($riderquery, $db->connection);
				if($riderresult) {
					$riderrow = mysql_fetch_array($riderresult);
					if($riderrow['NICKNAME'] == "") {
						$knightArray = $knightArray . $riderrow['FNAME'] . ' ' . $riderrow['LNAME'] . ', ';
					} else {
					$knightArray = $knightArray . $riderrow['NICKNAME'] . ', ';
					}
				}
			}
		}
		echo substr($knightArray,0,-2) . '<br><br></td></tr>';
    }
} else {
	echo 'No Rides Recorded.  Please use Ride Tracker link to enter rides.';
}
echo '</table></div>';
mysql_close($db->connection);
?>