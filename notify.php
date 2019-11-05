<!DOCTYPE html>
<html>
<body>

<h1>Notify PHP page</h1>

<?php
echo "Hello World - Notify!!!";
$logFilePath = 'logs/debug.txt';
ob_start();

// if you want to concatenate:
if (file_exists($logFilePath)) {
    include($logFilePath);
}
// for timestamp
$currentTime = date(DATE_RSS);

// echo log statement(s) here
echo "\n\n$currentTime - URL:";
echo $_SERVER['REQUEST_URI'];

$logFile = fopen($logFilePath, 'w');
fwrite($logFile, ob_get_contents());
fclose($logFile);
ob_end_flush();
?>

</body>
</html>

