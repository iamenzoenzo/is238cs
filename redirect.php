<?php 

$logFilePath = 'logs/debug.txt';
ob_start();

// if you want to concatenate:
if (file_exists($logFilePath)) {
    include($logFilePath);
}

echo "ACCESS TOKEN: ".$_GET["access_token"]."\n";
echo "MOBILE NUMBER: ".$_GET["subscriber_number"]."\n";

$logFile = fopen($logFilePath, 'w');
fwrite($logFile, ob_get_contents());
fclose($logFile);
ob_end_flush();

?>
