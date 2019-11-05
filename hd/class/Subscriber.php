<?php

class Subscriber extends Database{

    private $subscriberTable = 'hd_subscribers';
	private $dbConnect = false;
    
    public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    }

    public function getSubscriberInfo(){
        
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

    public function addSubscriber(){

        $sqlQuery = "";
        $result = mysqli_query($this->dbConnect, $sqlQuery);		
        
    }

    public function updateSubscriberInfo(){

        $sqlQuery = "";
        $result = mysqli_query($this->dbConnect, $sqlQuery);		
        
    }

    public function removeSubscriber(){

        $sqlQuery = "";
        $result = mysqli_query($this->dbConnect, $sqlQuery);		
        
    }
}

?>