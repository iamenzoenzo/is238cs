<?php $link = mysql_connect('teamlaban.chioee9qrhwf.us-east-1.rds.amazonaws.com', 'teamlaban', 'teamlaban'); if 
(!$link) { die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully'; mysql_close($link);
?>
