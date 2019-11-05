<!DOCTYPE html> <html> <body> <h1>Checking DB</h1> <?php echo "Checking DB!"; ?> <?php $link = 
mysql_connect('teamlaban.chioee9qrhwf.us-east-1.rds.amazonaws.com:3306', 'teamlaban', 'teamlaban'); if (!$link) { 
die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully'; mysql_close($link); ?> </body> </html>
