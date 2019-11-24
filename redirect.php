<?php
include 'helpdesk/init.php';
date_default_timezone_set('Asia/Manila');

//check if subscriber_number and access_token has value
if(isset($_GET["subscriber_number"])&& isset($_GET["access_token"])){
  
  $subscriberId = $subs->getSubscriberIdByNumber($_GET["subscriber_number"]);
  
  //if already subscribed
  if(isset($subscriberId)){

    $access_token = $subs->getAccessTokenById($subscriberId);

    //check if not the same access_token
    if($access_token!=$_GET["access_token"]){
      
      //update access_token
      $subs->updateSubscriber($subscriberId,$_GET["access_token"]);
    }
  }else{
    
    //not yet subscribed
    $subs->saveSubscriber($_GET["subscriber_number"],$_GET["access_token"]);
  }
}
else{
  echo "Invalid invoke! Please subscribe thru SMS.";
}
?>
