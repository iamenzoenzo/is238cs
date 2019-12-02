<?php
date_default_timezone_set('Asia/Manila');

// require '../init.php';
// require '../class/Database.php';

class Tickets extends Database {  
    private $ticketTable = 'Tickets';
	private $ticketRepliesTable = 'Ticket_replies';
	private $tableSchema = 'teamlaban';
	private $secondaryTableSchema = 'plemadb';
	private $dbConnect = false;

	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
	} 

	public function getAllTickets(){

		$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

		$sqlWhere = "
		WHERE
			t.Status is not null
			AND t.Status in ('Open','In Progress')
			AND (t.assignedTo is null or t.assignedTo = '".$user."')
		GROUP BY  t.MobileNumber ";

		$sqlQuery = "
		SELECT
			t.MobileNumber as subscriber_name,
			t.Status as thread_status
		FROM teamlaban.".$this->ticketTable." t". 
			$sqlWhere;

		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$ticketData = array();	
		
		while( $ticket = mysqli_fetch_assoc($result) ) {		
			$ticketRows = array();			
			$ticketRows[] = $ticket['subscriber_name'];
			$ticketRows[] = $ticket['thread_status'];
			$ticketRows[] = '<a href="../users/ticket_view.php?id='.$ticket['subscriber_name'].'" class="btn btn-success btn-xs view-action-btn"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>'.' '.
							'<a href="../users/ticket_details.php?id='.$ticket['subscriber_name'].'" class="btn btn-warning btn-xs update-action-btn"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>'.' '.
							'<a href="#" class="btn btn-warning btn-xs update-action-btn claimThreadBtn" value="'.$ticket['subscriber_name'].'" id="claimThreadBtn-'.$ticket['subscriber_name'].'"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span></a>';
			
			$ticketData[] = $ticketRows;
		}
		$output = array(
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$ticketData
		);
		
		return $output['data'];
	}

	public function getRepliedTitle($title) {
		$title = $title.'<span class="answered">Answered</span>';
		return $title; 		
	}
	
	public function createTicket($subscriberId, $message, $timestamp, $status, $user) {      

		if(!empty($_POST['subscriberId']) && !empty($_POST['message'])) {                
			
			$date = new DateTime();
			$date = $date->getTimestamp();
			
			$queryInsert = "
				INSERT INTO ".$this->tableSchema.".".$this->ticketTable."
					(MobileNumber, message, assignedTo, createDate, CreatedBy, Status)
				VALUES
					('".$subscriberId."', '".$message."', '".$user."', '".$timestamp."', '".$user."', '".$status."');
				";			
			
			mysqli_query($this->dbConnect, $queryInsert);

			$this->updateSubscriberMessageInfo($subscriberId,'Closed','status');
			$this->sendSmsReply($subscriberId,$message);
		}
	}

	public function updateSubscriberMessageInfo($subscriberId,$ticketInfo,$infoType){

		$updateTicket = " UPDATE ".$this->tableSchema.".".$this->ticketTable." SET ";
		$sqlWhere = "WHERE MobileNumber = '".$subscriberId."' AND Status ='In Progress'";
		
		if($infoType == 'status'){
			$updateTicket .= " Status = '".$ticketInfo."' ".$sqlWhere;
		}

		mysqli_query($this->dbConnect, $updateTicket);
	}

	public function sendSmsReply($MobileNo,$message){
		$access_token = $subs->getAccessTokenByMobileNumber($MobileNo);
		$outbound->sendSms($api_short_code,$access_token,$MobileNo,$message);

	}

	public function updateThreadMessageStatus($subscriberId,$status,$assignee){

		$sqlWhere = " WHERE MobileNumber = ".$subscriberId." AND Status in ('Open') ";
		$updateTicket = " UPDATE ".$this->tableSchema.".".$this->ticketTable." SET Status = 'In Progress'".$sqlWhere;

		mysqli_query($this->dbConnect, $updateTicket);

		$this->assignAgent($subscriberId,$assignee);

		return "success";
	}
	
	public function updateTicketInfo($ticketId,$ticketInfo,$infoType){
		
		$updateTicket = "UPDATE ".$this->tableSchema.".".$this->ticketTable." SET ";
		$sqlWhere = "WHERE idTickets = ".$ticketId;
		
		if($infoType == 'status'){
			$updateTicket .= " Status = '".$ticketInfo."' ".$sqlWhere;
		}
		if($infoType == 'assignee'){
			$updateTicket .= " assignedTo = ".$ticketInfo." ".$sqlWhere;
		}

		mysqli_query($this->dbConnect, $updateTicket);

		return [$this->getTicketDetails($ticketId),$updateTicket];
	}

	public function getTicketDetails($id) {  		
		$sqlQuery = "
			SELECT 
				t.idTickets as ticket_id,
				t.TicketReference as ticket_reference,
				t.MobileNumber as ticket_author,
				t.message as ticket_message,
				t.assignedTo as ticket_assignee,
				t.createDate as ticket_creation,
				t.status as ticket_status
			FROM teamlaban.".$this->ticketTable." t 
			WHERE t.idTickets = ".$id;	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		return $row;       
	} 

	public function getSubscriberMessages($subscriberNumber){

		$sqlWhere = "
		WHERE
				t.CreatedBy is not null
				-- AND t.CreatedBy = t.MobileNumber
				-- and createdby in list of users
				AND t.MobileNumber = '".$subscriberNumber."'
		";
		
		$sqlQuery = "
			SELECT 
				t.idTickets as message_id,
				t.MobileNumber as subscriber_id,
				t.message as message,
				t.createDate as message_created,
				CASE
					WHEN t.CreatedBy = t.MobileNumber THEN t.MobileNumber
					WHEN t.CreatedBy = u.id THEN concat(u.fname,'',u.lname)
				END as message_creator,
				t.expiry_date as message_expiry
			FROM
				teamlaban.".$this->ticketTable." t
				LEFT JOIN ".$this->secondaryTableSchema.".users u on u.id = t.assignedTo and u.id is not null
			".$sqlWhere." ORDER BY t.createDate DESC";	

		$result = mysqli_query($this->dbConnect, $sqlQuery);
		
		while( $ticket = mysqli_fetch_assoc($result) ) {		
			$ticketRows = array();			
			$ticketRows[] = $ticket['message_id'];
			$ticketRows[] = $ticket['subscriber_id'];
			$ticketRows[] = $ticket['message'];
			$ticketRows[] = $ticket['message_created'];
			$ticketRows[] = $ticket['message_creator'];
			$ticketRows[] = $ticket['message_expiry'];

			$ticketData[] = $ticketRows;
		}
		
		return $ticketData;
		
		return $row;  
	}
	   
	public function saveTicketReplies () {

		
		if($_POST['message']) {
			$date = new DateTime();
			$date = $date->getTimestamp();
			
			$queryInsert = "INSERT INTO ".$this->ticketRepliesTable." (user, text, ticket_id, date) 
				VALUES('".$_SESSION["userid"]."', '".$_POST['message']."', '".$_POST['ticketId']."', '".$date."')";
			mysqli_query($this->dbConnect, $queryInsert);				
			
			$updateTicket = "UPDATE ".$this->ticketTable." 
				SET last_reply = '".$_SESSION["userid"]."', user_read = '0', admin_read = '0' 
				WHERE id = '".$_POST['ticketId']."'";				
			mysqli_query($this->dbConnect, $updateTicket);
		} 

		echo ['success'=> true];
	}	

	public function getTicketReplies($id) {  		
		$sqlQuery = "
			SELECT
				t.idTicket_replies as reply_id,
				t.ticket_id as ticket_id,
				t.Agent as reply_author,
				t.Reply as reply_message,
				t.date_modified as reply_modified,
				t.created as reply_created
			FROM
				teamlaban.".$this->ticketRepliesTable." t
			WHERE
				t.ticket_id = ".$id."
			ORDER BY t.created
		";	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
       	$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;            
		}
        return $data;
	}

	//assign agent to ticket, set status to In Progress and set expiry to 24 hours
	public function assignAgent($MobileNumber,$AgentName){
		$expiry_date = date("Y/m/d H:i:s", strtotime('now + 1 days'));
		
		$sqlQuery = "UPDATE Tickets"."
		SET assignedTo = '".$AgentName."',
		Status='In Progress', expiry_date='".$expiry_date."' 
		WHERE MobileNumber = '".$MobileNumber."'
		AND Status <> 'Closed';";

		mysqli_query($this->dbConnect, $sqlQuery);
		return "success";	
	}
}