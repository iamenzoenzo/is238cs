<?php
date_default_timezone_set('Asia/Manila');

class Subscriber extends Database {
    private $subscribersTable = 'Subscribers';
    private $dbConnect = false;

public function __construct(){
    $this->dbConnect = $this->dbConnect();
}

public function saveSubscriber($Mobile_Number,$Access_Token) {

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
    }else{
        return 0;
    }
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

public function getAccessTokenByMobileNumber($mobileNumber){
    //final code
    $sqlQuery = "SELECT Subscribers.accessToken FROM ".$this->subscribersTable.
    " WHERE mobileNumber='".$mobileNumber."'";
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

public function updateSubscriber($mobileNo,$accessToken){

    $updated_date = date("Y/m/d H:i:s", strtotime('now')); 
    $sqlQuery = "
    UPDATE ".$this->subscribersTable."
    SET accessToken = '".$accessToken."', date_modified='".$updated_date."' 
    WHERE mobileNumber = '".$mobileNo."';";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "success";

}

}
