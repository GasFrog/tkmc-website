<?php

if (!defined('SCRIPTS_DIR')) {
	define('SCRIPTS_DIR', '/home1/templark/scripts');
}
require_once(SCRIPTS_DIR.'/includes/defines.inc');
require_once(SCRIPTS_DIR.'/classes/Database.class');

//Connect To Database
$db = LocalDatabase::getWritable('master');
$table='quotes';
$quote = $_POST['quote'];
$knight_num = $_POST['knightNum'];

$query = 'INSERT INTO ' . $table . ' (quote, knight_num)
  VALUES ("' . $quote . '", "' . $knight_num . '")';

$result = mysql_query($query, $db->connection);
if($result) {
   header("Location: http://templarknightsmc.com/member-area/templar-wisdom?success=1");
}
else {
  echo 'Failed to Create Record';
}
mysql_close($db->connection);
?>