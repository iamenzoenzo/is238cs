<?php
include 'helpdesk/init.php';
date_default_timezone_set('Asia/Manila');

$autoReplyMessageNewSubscriber="Welcome to PLEMA! Your trusted emergency app! Thank you for subscribing. Best regards, #TeamLaban.";
$autoReplyMessageReturningSubscriber="Welcome back to PLEMA! Thank you for trusting us. Best regards, #TeamLaban.";

//check if subscriber_number and access_token has value
if(isset($_GET["subscriber_number"])&& isset($_GET["access_token"])){
  
  $subscriberId = $subs->getSubscriberIdByNumber($_GET["subscriber_number"]);
  
  //if already subscribed
  if($subscriberId!=0){
    $access_token = $subs->getAccessTokenById($subscriberId);

    //check if not the same access_token
    if($access_token!=$_GET["access_token"]){
      
      //update access_token
      $subs->updateSubscriber($_GET["subscriber_number"],$_GET["access_token"]);
      $outbound->sendSms($api_short_code, $_GET["access_token"],$_GET["subscriber_number"],$autoReplyMessageReturningSubscriber);
      
    }
  }else{
    //not yet subscribed
    $subs->saveSubscriber($_GET["subscriber_number"],$_GET["access_token"]);
    $outbound->sendSms($api_short_code, $_GET["access_token"],$_GET["subscriber_number"],$autoReplyMessageNewSubscriber);
    
  }
}
else{
  echo "Invalid invoke! Please subscribe thru SMS.";
}
?>
