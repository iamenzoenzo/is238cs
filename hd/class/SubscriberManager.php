<?php

class SubscriberManager{

    
    public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    }

    // get number, access token from POST data from API
    public function getSubscriberInfo(){

        return 0;
    }
    
    // insert number, access token to subscriber table
    public function saveSubscriberInfo($subscriberInfo){
        
        return 0;
    }

    // retrieve access token from subscriber table
    public function retrieveAccessToken($subscriberId){

        return 0;
    }

    // update access token of subscriber
    public function updateSubscriberAccessToken($subscriberId, $newAccessToken){

        return 0;
    }

}

?>