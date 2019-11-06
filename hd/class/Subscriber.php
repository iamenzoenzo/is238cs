<?php

class Subscriber extends Database{

    private $subscriberTable = 'hd_subscribers';
    private $database = 'teamlaban';
	private $dbConnect = false;
    
    public function __construct(){		
        $this->dbConnect = $this->dbConnect();
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

    public function addNewSubscriber($subscriberId,$subscriberNumber,$accessToken){

        $sqlQuery = "
            INSERT INTO ".$database.".".$this->subscriberTable." (`id`,`subscriber_number`,`subscriber_access_token`)
            VALUES (".$subscriberId.", '".$subscriberNumber."','".$accessToken."');
            ";

        $result = mysqli_query($this->dbConnect, $sqlQuery);		
        
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
}

?>