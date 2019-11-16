<?php 
include 'hd/init.php'; 

if(isset($_GET["subscriber_number"])&& isset($_GET["access_token"])){
  $subs->saveSubscriber($_GET["subscriber_number"],$_GET["access_token"]);
}else{echo "Invalid invoke! Please subscribe thru SMS.";}


//echo $subs->getSubscriberId($_GET["subscriber_number"]);

?>