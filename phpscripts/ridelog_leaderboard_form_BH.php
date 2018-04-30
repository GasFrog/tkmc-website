<?php

if (!defined('SCRIPTS_DIR')) {
	define('SCRIPTS_DIR', '/home1/templark/scripts');
}
require_once(SCRIPTS_DIR.'/includes/defines.inc');
require_once(SCRIPTS_DIR.'/classes/Database.class');
	
//Connect To Database
$db = LocalDatabase::getReadable('master');
$ktable='knight_directory';
$rtable='ride_directory';
$ctable='ride_corollary';
$miles_array='';
$lodgekey =  get_post_custom_values("lodge_num");
$lodgeNum = $lodgekey[0];
$mileage = 0;
$array_num = 0;
$query = '';

if($lodgeNum < 1) {
	$query = 'SELECT * FROM '.$ktable.' WHERE DATE_WITHDREW=0000-00-00';
} else {
	$query = 'SELECT * FROM ' . $ktable . ' WHERE LODGE = '.$lodgeNum.' && DATE_WITHDREW=0000-00-00';
}
$result = mysql_query($query, $db->connection);

if($result) {
	while($row = mysql_fetch_array($result)) {
  		$query = 'SELECT k.NICKNAME, k.FNAME, m.MILES FROM knight_directory AS k INNER JOIN ride_corollary AS c ON k.RECORD_NUM=c.KNIGHT_NUM JOIN ride_directory AS m on c.RIDE_NUM=m.RIDE_NUM WHERE k.RECORD_NUM = ' . $row['RECORD_NUM'];
		$inner_result = mysql_query($query);
  		if ($inner_result) {
  			$mileage = 0;
			while($inner_row = mysql_fetch_array($inner_result)){
    			$mileage = $mileage + $inner_row['MILES'];
  			}
		}
		if ($row['NICKNAME'] == "") {
			$miles_array[$row['FNAME'].'_'.$row['RECORD_NUM']] = $mileage;
		} else {
			$miles_array[$row['NICKNAME'].'_'.$row['RECORD_NUM']] = $mileage;
		}
		$array_num += 1;
	}
}
print_leaders($miles_array);
mysql_close($db->connection);

function print_leaders($array) {
$i = 0;
echo '<table>';
arsort($array);
foreach ($array as $key => $value) {	
		if ($i < 10) {
                     echo '<tr><td align="left">' . substr($key, 0, strpos($key, '_')) . '</td><td> ' . $value .   '</td></tr>';
                     $i++;
                } else { break; }
	}
echo '</table>';
}	
?>