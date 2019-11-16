<?php 
include 'hd/init.php'; 


//FINAL CODE SAVINGS SUBSCRIBERS
if(isset($_GET["subscriber_number"])&& isset($_GET["access_token"])){

  $subscriberId = $subs->getSubscriberIdByNumber($_GET["subscriber_number"]);

  if(isset($subscriberId)){
    $access_token = $subs->getAccessTokenById($subscriberId);
    if($access_token!=$_GET["access_token"]){
      //update access token
      $subs->updateSubscriber($subscriberId,$_GET["access_token"]);
    }else{
      $subs->saveSubscriber($_GET["subscriber_number"],$_GET["access_token"]);
    }
  }
  
}
else{
  echo "Invalid invoke! Please subscribe thru SMS.";
}










?>