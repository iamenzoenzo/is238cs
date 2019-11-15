<?php

class Subscriber extends Database{

    private $subscriberTable = 'hd_subscribers';
    private $database = 'teamlaban';
    private $dbConnect = false;
    
    public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    }

    public function getSubscriberInfoFromDb(){
        
        // Update function to get subscriber info using mobile number instead of access token
        
        if(!empty($_SESSION["subaccesstoken"])) {
			$sqlQuery = "SELECT * FROM ".$this->subscriberTable." WHERE subscriber_access_token ='".$_SESSION["subaccesstoken"]."'";
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

    public function addNewSubscriber($subscriberId,$subscriberNumber,$accessToken){

        $sqlQuery = "
            INSERT INTO ".$database.".".$this->subscriberTable." (`id`,`subscriber_number`,`subscriber_access_token`)
            VALUES (".$subscriberId.", '".$subscriberNumber."','".$accessToken."');
            ";

        $result = mysqli_query($this->dbConnect, $sqlQuery);		
        
    }

    public function updateSubscriberInfo($subscriberId,$subscriberNumber,$accessToken){

        // Update function to get subscriber info using mobile number instead of access token
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

    // get number, access token from POST data from API
    public function getSubscriberInfoFromAPI(){

        $sqlQuery = " ";
        $subscriberInfo = mysqli_query($this->dbConnect, $sqlQuery);

        return $subscriberInfo;
    }
    
    // insert number, access token to subscriber table
    public function saveSubscriberInfo($subscriberInfo){

        $sqlQuery = " ";
		mysqli_query($this->dbConnect, $sqlQuery);
        
    }

    // retrieve access token from subscriber table
    public function retrieveAccessToken($subscriberId){

        $sqlQuery = " ";
		$subAccessToken = mysqli_query($this->dbConnect, $sqlQuery);

        return $subAccessToken;
    }

    // update access token of subscriber
    public function updateSubscriberAccessToken($subscriberId, $newAccessToken){

        $sqlQuery = " ";
		mysqli_query($this->dbConnect, $sqlQuery);
    }

    public function getSubscriberMessages(){
        // Function to retrieve messages, from Subscriber_Messages, belonging to subscriber
        
        $sqlQuery = " ";
        $subscriberMessages = mysqli_query($this->dbConnect, $sqlQuery);
        
        return $subscriberMessages;
    }

    public function mergeSubscriberMessages(){
        // Function to combine/merge multiple messages received for a subscriber

        // retrieve all messages for a subscriber
        $sqlQuery = " ";
        $subscriberMessages = mysqli_query($this->dbConnect, $sqlQuery);

        // merge messages
        $mergedSubscriberMessages=""; 

        return $mergedSubscriberMessages;
    }
}

?>