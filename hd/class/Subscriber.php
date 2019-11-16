<?php

class Subscriber extends Database {  
    private $subscribersTable = 'Subscribers';
    private $dbConnect = false;

public function __construct(){		
    $this->dbConnect = $this->dbConnect();
} 
 
public function saveSubscriber($Mobile_Number,$Access_Token) {
		
    $date = new DateTime();
    $date = $date->getTimestamp();
    $sqlQuery = "INSERT INTO ".$this->subscribersTable." (mobileNumber, accessToken) 
                VALUES('".$Mobile_Number."', '".$Access_Token."')";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    return $result;
}
    
public function getSubscriberIdByNumber($subscriberNumber){

    $sqlQuery = "SELECT Subscribers.idSubscribers FROM ".$this->subscribersTable.
    " WHERE  mobileNumber='".$subscriberNumber."'";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $row = mysqli_fetch_array($result);
    $userId = $row['idSubscribers']; 
    if($userId!=0){
    return $userId;
    }else{return 0;}
}

public function getAccessTokenById($subscriberId){
    //final code
    $sqlQuery = "SELECT Subscribers.accessToken FROM ".$this->subscribersTable.
    " WHERE idSubscribers='".$subscriberId."'";
    $result = mysqli_query($this->dbConnect, $sqlQuery);		
    $row = mysqli_fetch_array($result);
    $access_token = $row['accessToken']; 
    if(isset($access_token)){
        return $access_token;
    }
    else{
        return 0;
    }     
}

public function getSubscriberNumberById($subscriberId){
    //final code
    $sqlQuery = "SELECT Subscribers.mobileNumber FROM ".$this->subscribersTable.
    " WHERE idSubscribers='".$subscriberId."'";
    $result = mysqli_query($this->dbConnect, $sqlQuery);		
    $row = mysqli_fetch_array($result);
    $mobileNumber = $row['mobileNumber']; 
    return mobileNumber;
    
}

public function updateSubscriber($subscriberId,$accessToken){

    $sqlQuery = "
    UPDATE ".$database."".$this->subscribersTable." 
    SET accessToken = '".$accessToken."'
    WHERE idSubscribers = ".$subscriberId.";";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "success";
    
}




/*

public function getSubscriberInfoByMobileNumber($MobileNumber){
    
    if(!empty($MobileNumber)) {
        $sqlQuery = "SELECT * FROM ".$this->subscribersTable." WHERE mobileNumber ='".$MobileNumber."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);		
        $userDetails = mysqli_fetch_assoc($result);        
        return $userDetails;
    }
}

public function getSubscriberIdByNumber($subscriberNumber){

    $sqlQuery = "SELECT Subscribers.idSubscribers FROM ".$this->subscribersTable." WHERE  mobileNumber='".$subscriberNumber."'";
    $result = mysqli_query($this->dbConnect, $sqlQuery);		
    $userId = mysqli_fetch_assoc($result);
    if($userId!=0){
        return $userId['idSubscribers'];
    }else{return 0;}
    
    
}


    

public function getSubscriberInfoFromDb(){
    
    if(!empty($_SESSION["userid"])) {
        $sqlQuery = "SELECT * FROM ".$this->subscriberTable." WHERE id ='".$_SESSION["userid"]."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);		
        $userDetails = mysqli_fetch_assoc($result);
        
        return $userDetails;
    }
}

public function getAllSubscribers(){

    $sqlQuery = "SELECT * FROM ".$this->subscriberTable;
    $result = mysqli_query($this->dbConnect, $sqlQuery);		
    $userDetails = mysqli_fetch_assoc($result);
    
    return $userDetails;
}




  public function updateSubscriberInfo($subscriberId,$subscriberNumber,$accessToken){

      $sqlQuery = "
      UPDATE ".$database.".".$this->subscriberTable." 
      SET `subscriber_number` = '".$subscriberNumber."', `subscriber_access_token` = '".$accessToken."'
      WHERE `id` = ".$subscriberId.";
      ";
      $result = mysqli_query($this->dbConnect, $sqlQuery);		
      
  }

  public function removeSubscriber(){

      $sqlQuery = "
          DELETE FROM ".$database.".".$this->subscriberTable." WHERE `id` = ".$subscriberId.";
      ";
      $result = mysqli_query($this->dbConnect, $sqlQuery);		
      
  }

 */

}