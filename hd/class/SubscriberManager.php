<?php

class SubscriberManager extends Database{

    private $ticketTable = 'hd_tickets';
	private $ticketRepliesTable = 'hd_ticket_replies';
	private $subscriberTable = 'hd_subscribers';
	private $dbConnect = false;
    
    public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    }

    

}

?>