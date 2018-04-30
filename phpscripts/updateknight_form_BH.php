<?php

if (!defined('SCRIPTS_DIR')) {
	define('SCRIPTS_DIR', '/home1/templark/scripts');
}
require_once(SCRIPTS_DIR.'/includes/defines.inc');
require_once(SCRIPTS_DIR.'/classes/Database.class');
require_once(SCRIPTS_DIR.'/includes/form_functions.inc');
	
//Connect To Database
$db = LocalDatabase::getReadable('master');
$ktable='knight_directory';
$grand_council = true;
$councilKey=get_post_custom_values("grand_council");
if ($councilKey[0] == 0) {
	$grand_council = false;
}

if(isset($_GET["success"])) {
	$query = 'SELECT * FROM ' . $ktable . ' WHERE RECORD_NUM = ' . $_GET["knightNum"];
    $result = mysql_query($query, $db->connection);
    if($result) {
		$row = mysql_fetch_array($result);
		echo '<p>You successfully updated the record for ' . $row['FNAME'] . ' ' . $row['LNAME'] . '.</p>';
    }
}

if(isset($_GET["knightNum"])) {
    $query = 'SELECT * FROM ' . $ktable . ' WHERE RECORD_NUM = ' . $_GET["knightNum"];
    $result = mysql_query($query, $db->connection);
    if($result) {
		$row = mysql_fetch_array($result);
		echo '<p>You are modifying ' . $row['FNAME'] . ' ' . $row['LNAME'] . '.</p>';
    }
} 

if ($grand_council) {
	echo '<form action="http://templarknightsmc.com/council-area/grand-council/update-knight" method="get">';
} else {
	echo '<form action="http://templarknightsmc.com/council-area/update-knight" method="get">';
}
members_dropdown($db, $ktable, true, 0, 'Who do you want to update?');
echo '<input type="submit" value="Find Individual!"></form>';
echo '<p><hr></p>';

if(isset($_GET["knightNum"])) {
	echo '<form action="http://templarknightsmc.com/scripts/updateknight_action.php" method="post">';
	$query = 'SELECT * FROM ' .  $ktable . ' WHERE RECORD_NUM = ' . $_GET['knightNum'];
	$result = mysql_query($query, $db->connection);
	if($result) {
		$row = mysql_fetch_array($result);
		if ($grand_council) {
			echo '<p>TKMC Record Number: <input type="text" name="tkmcNum" value="' . $row['TKMC_NUM'] . '" /></p>';
		} else {
			echo '<p>TKMC Record Number: '. $row['TKMC_NUM'] . '</p>
			<input type="hidden" name="tkmcNum" value="'.$row['TKMC_NUM'].'" />';
		}
		echo 'First Name: <input type="text" name="firstName" value="' . $row['FNAME'] . '" /><p>';
		echo 'Last Name: <input type="text" name="lastName" value="' . $row['LNAME'] . '" /><p>';
		echo 'Birth Date (yyyy-mm-dd): <input type="text" name="birthDate" value="' . $row['BIRTH_DATE'] . '" /><p>';
		echo 'Nickname: <input type="text" name="nickName" value="' . $row['NICKNAME'] . '" /><p>';
		if ($grand_council) {
			rank_dropdown($db, $ktable, $row['RANK']);
		} else {
			echo 'Rank: '.$row['RANK'];
			echo '<input type="hidden" name="rank" value="' . get_rank($db, $ktable, $row['RANK']) . '" />';
		}
		echo '</p>';
		echo 'Street Address: <input type="text" name="streetAddress" value="' . $row['STREET_ADDRESS'] . '" /><p>';
		echo 'City: <input type="text" name="city" value="' . $row['CITY'] . '" /><p>';
		echo '<p>State:';
		echo '<select name="state">';
		$usertable='state_directory';
		$query = 'SELECT * FROM ' . $usertable;
		$stateresult = mysql_query($query, $db->connection);
		if($stateresult) {
			while($staterow = mysql_fetch_array($stateresult)){
				if($staterow['STATE_NUM'] == $row['STATE']) {
					echo '<option selected="yes" value="' . $staterow['STATE_NUM'] . '">' . $staterow['STATE_NAME'] . '</option>';
				} else {
					echo '<option value="' . $staterow['STATE_NUM'] . '">' . $staterow['STATE_NAME'] . '</option>';
				}
			}
		}
		echo '</select></p>';
		echo 'Zip Code: <input type="text" name="zipCode" value="' . $row['ZIP_CODE'] . '" /><p>';
		echo '<p>Country:';
		echo '<select name="country">';
		$usertable='country_directory';
		$query = 'SELECT * FROM ' . $usertable;
		$countryresult = mysql_query($query, $db->connection);
		if($countryresult) {
			while($countryrow = mysql_fetch_array($countryresult)){
				if($countryrow['COUNTRY_NUM'] == $row['COUNTRY']) {
					echo '<option selected="yes" value="' . $countryrow['COUNTRY_NUM'] . '">' . $countryrow['COUNTRY_NAME'] . '</option>';
				} else {
					echo '<option value="' . $countryrow['COUNTRY_NUM'] . '">' . $countryrow['COUNTRY_NAME'] . '</option>';
				}
			}
		}
		echo '</select></p>';
		echo 'Phone (no dashes): <input type="text" name="phoneNum" value="' . $row['PHONE'] . '" /><p>';
		echo 'Email Address: <input type="text" name="emailAddr" value="' . $row['EMAIL'] . '" /><p>';
		echo '<p>Lodge:';
		echo '<select name="lodgeNum">';
		$lodgeArray = "";
		$usertable='lodge_directory';
		$query = 'SELECT * FROM ' . $usertable;
		$lodgeresult = mysql_query($query, $db->connection);
		if($lodgeresult) {
			while($lodgerow = mysql_fetch_array($lodgeresult)){
				$lodgeArray[$lodgerow['LODGE_NUM']] = $lodgerow['LODGE_NAME'];
				if ($grand_council) {
					if($lodgerow['LODGE_NUM'] == $row['LODGE']) {
						echo '<option selected="yes" value="' . $lodgerow['LODGE_NUM'] . '">' . $lodgerow['LODGE_NAME'] . '</option>';
					} else {
						echo '<option value="' . $lodgerow['LODGE_NUM'] . '">' . $lodgerow['LODGE_NAME'] . '</option>';
					}
				} else {
				if ($lodgerow['LODGE_NUM'] == $row['LODGE']) {
						echo '<option selected="yes" value="'.$lodgerow['LODGE_NUM'].'">'.$lodgerow['LODGE_NAME'].'</option>';
					}
				}
			}
			echo '</select></p>';
		}
		echo 'Referred by (use nickname if posible): <input type="text" name="referredBy" value="' . $row['REFERRED_BY'] . '"/><p>';
		echo '<p>Mentor:';
		echo '<select name="mentor">';
		echo '<option value="">--- Select ---</option>';
		$usertable='knight_directory';
		$query = 'SELECT * FROM ' . $usertable . ' ORDER BY LODGE, NICKNAME';
		$mentorresult = mysql_query($query, $db->connection);
		if($mentorresult) {
			while($mentorrow = mysql_fetch_array($mentorresult)) {
				if($mentorrow['RANK'] != "Provisional" && $mentorrow['RANK'] != "Honorary") {
					if($mentorrow['RECORD_NUM'] == $row['MENTOR']) {
						echo '<option selected="yes" value="' . $mentorrow['RECORD_NUM'] . '">' . $mentorrow['FNAME'] . ' "' . $mentorrow['NICKNAME'] . '" ' . $mentorrow['LNAME'] . ' [' . $lodgeArray[$mentorrow['LODGE']] . ']</option>';
					} else {
						echo '<option value="' . $mentorrow['RECORD_NUM'] . '">' . $mentorrow['FNAME'] . ' "' . $mentorrow['NICKNAME'] . '" ' . $mentorrow['LNAME'] . ' [' . $lodgeArray[$mentorrow['LODGE']] . ']</option>';
					}
				}
			}
		}
		echo '</select></p>';
		echo '<b>Please enter all Dates in the format of yyyy-mm-dd </b><br>';
		if ($grand_council) {
			echo 'Date Applied: <input type="text" name="dateApplied" value="' . $row['DATE_JOINED'] . '" /><p>';
		} else {
			echo 'Date Applied: '. $row['DATE_JOINED'] . '<p>';
			echo '<input type="hidden" name="dateApplied" value="' . $row['DATE_JOINED'] . '" />';
		}
		if ($grand_council) {
			echo 'Date Approved: <input type="text" name="dateApproved" value="' . $row['DATE_APPROVED'] . '" /><p>';
		} else {
			echo 'Date Approved: '. $row['DATE_APPROVED'] . '<p>';
			echo '<input type="hidden" name="dateApproved" value="' . $row['DATE_APPROVED'] . '" />';
		}
		if ($grand_council) {		
			echo 'Date Knighted: <input type="text" name="dateKnighted" value="' . $row['DATE_KNIGHTED'] . '" /><p>';
		} else {
			echo 'Date Knighted: '. $row['DATE_KNIGHTED'] . '<p>';
			echo '<input type="hidden" name="dateKnighted" value="' . $row['DATE_KNIGHTED'] . '" />';
		}
		echo 'Date WITHDREW: <input type="text" name="dateWithdrew" value="' . $row['DATE_WITHDREW'] . '" /><p>';
		echo 'Notes: <input type="textarea" name="notes" value="' . $row['NOTES'] . '" /><p>';
		echo '<input type="hidden" name="recordNum" value="' . $row['RECORD_NUM'] . '" />';
		echo '<input type="hidden" name="grandCouncil" value="'.$grand_council.'" />';
		echo '<p><input type="submit" value="Update Record"></p>';
	}
}
echo '</form>';
mysql_close($db->connection);
?>