<?php
date_default_timezone_set('Asia/Manila');

// require '../init.php';
// require '../class/Database.php';

class Tickets extends Database {  
    private $ticketTable = 'Tickets';
	private $ticketRepliesTable = 'Ticket_replies';
	private $tableSchema = 'teamlaban';
	private $dbConnect = false;

	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
	} 
	public function getAllTickets(){

		$user = $_SESSION['user'];

		$sqlWhere = "
		WHERE
			t.Status is not null
			AND t.Status = 'Open'
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
							'<a href="../users/ticket_details.php?id='.$ticket['subscriber_name'].'" class="btn btn-warning btn-xs update-action-btn"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>';
			
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
	
	public function createTicket($subscriberId, $message, $timestamp, $status) {      

		$user = $_SESSION['user'];
		
		if(!empty($_POST['subscriberId']) && !empty($_POST['message'])) {                
			
			$date = new DateTime();
			$date = $date->getTimestamp();
			$uniqid = uniqid(); 
			
			$queryInsert = "
				INSERT INTO ".$this->tableSchema.".".$this->ticketTable."
					(MobileNumber, message, assignedTo, createDate, CreatedBy, Status)
				VALUES
					('".$subscriberId."', '".$message."', '".$user."', '".$timestamp."', '".$user."', '".$status."');
				";			
			
			mysqli_query($this->dbConnect, $queryInsert);	
		}
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
				t.CreatedBy as message_creator,
				t.expiry_date as message_expiry
			FROM
				teamlaban.".$this->ticketTable." t
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

	
	// public function getRepliedTitle($title) {
	// 	$title = $title.'<span class="answered">Answered</span>';
	// 	return $title; 		
	// }
	// public function createTicket() {      
	// 	if(!empty($_POST['subject']) && !empty($_POST['message'])) {                
	// 		$date = new DateTime();
	// 		$date = $date->getTimestamp();
	// 		$uniqid = uniqid();                
	// 		$message = strip_tags($_POST['subject']);              
	// 		$queryInsert = "INSERT INTO ".$this->ticketTable." (uniqid, user, title, init_msg, department, date, last_reply, user_read, admin_read, resolved) 
	// 		VALUES('".$uniqid."', '".$_SESSION["userid"]."', '".$_POST['subject']."', '".$message."', '".$_POST['department']."', '".$date."', '".$_SESSION["userid"]."', 0, 0, '".$_POST['status']."')";			
	// 		mysqli_query($this->dbConnect, $queryInsert);			
	// 		echo 'success ' . $uniqid;
	// 	} else {
	// 		echo '<div class="alert error">Please fill in all fields.</div>';
	// 	}
	// }	
	// public function getTicketDetails(){
	// 	if($_POST['ticketId']) {	
	// 		$sqlQuery = "
	// 			SELECT * FROM ".$this->ticketTable." 
	// 			WHERE id = '".$_POST["ticketId"]."'";
	// 		$result = mysqli_query($this->dbConnect, $sqlQuery);	
	// 		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	// 		echo json_encode($row);
	// 	}
	// }
	// public function updateTicket() {
	// 	if($_POST['ticketId']) {	
	// 		$updateQuery = "UPDATE ".$this->ticketTable." 
	// 		SET title = '".$_POST["subject"]."', department = '".$_POST["department"]."', init_msg = '".$_POST["message"]."', resolved = '".$_POST["status"]."'
	// 		WHERE id ='".$_POST["ticketId"]."'";
	// 		$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
	// 	}	
	// }		
	// public function closeTicket(){
	// 	if($_POST["ticketId"]) {
	// 		$sqlDelete = "UPDATE ".$this->ticketTable." 
	// 			SET resolved = '1'
	// 			WHERE id = '".$_POST["ticketId"]."'";		
	// 		mysqli_query($this->dbConnect, $sqlDelete);		
	// 	}
	// }	
	// public function getDepartments() {       
	// 	$sqlQuery = "SELECT * FROM ".$this->departmentsTable;
	// 	$result = mysqli_query($this->dbConnect, $sqlQuery);
	// 	while($department = mysqli_fetch_assoc($result) ) {       
    //         echo '<option value="' . $department['id'] . '">' . $department['name']  . '</option>';           
    //     }
    // }	    
    // public function ticketInfo($id) {  		
	// 	$sqlQuery = "SELECT t.id, t.uniqid, t.title, t.user, t.init_msg as message, t.date, t.last_reply, t.resolved, u.nick_name as creater, d.name as department 
	// 		FROM ".$this->ticketTable." t 
	// 		LEFT JOIN hd_users u ON t.user = u.id 
	// 		LEFT JOIN hd_departments d ON t.department = d.id 
	// 		WHERE t.uniqid = '".$id."'";	
	// 	$result = mysqli_query($this->dbConnect, $sqlQuery);
    //     $tickets = mysqli_fetch_assoc($result);
    //     return $tickets;        
    // }    
	// public function saveTicketReplies () {
	// 	if($_POST['message']) {
	// 		$date = new DateTime();
	// 		$date = $date->getTimestamp();
	// 		$queryInsert = "INSERT INTO ".$this->ticketRepliesTable." (user, text, ticket_id, date) 
	// 			VALUES('".$_SESSION["userid"]."', '".$_POST['message']."', '".$_POST['ticketId']."', '".$date."')";
	// 		mysqli_query($this->dbConnect, $queryInsert);				
	// 		$updateTicket = "UPDATE ".$this->ticketTable." 
	// 			SET last_reply = '".$_SESSION["userid"]."', user_read = '0', admin_read = '0' 
	// 			WHERE id = '".$_POST['ticketId']."'";				
	// 		mysqli_query($this->dbConnect, $updateTicket);
	// 	} 
	// }	
	// public function getTicketReplies($id) {  		
	// 	$sqlQuery = "SELECT r.id, r.text as message, r.date, u.nick_name as creater, d.name as department, u.user_group  
	// 		FROM ".$this->ticketRepliesTable." r
	// 		LEFT JOIN ".$this->ticketTable." t ON r.ticket_id = t.id
	// 		LEFT JOIN hd_users u ON r.user = u.id 
	// 		LEFT JOIN hd_departments d ON t.department = d.id 
	// 		WHERE r.ticket_id = '".$id."'";	
	// 	$result = mysqli_query($this->dbConnect, $sqlQuery);
    //    	$data= array();
	// 	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	// 		$data[]=$row;            
	// 	}
    //     return $data;
    // }
	// public function updateTicketReadStatus($ticketId) {
	// 	$updateField = '';
	// 	if(isset($_SESSION["admin"])) {
	// 		$updateField = "admin_read = '1'";
	// 	} else {
	// 		$updateField = "user_read = '1'";
	// 	}
	// 	$updateTicket = "UPDATE ".$this->ticketTable." 
	// 		SET $updateField
	// 		WHERE id = '".$ticketId."'";				
	// 	mysqli_query($this->dbConnect, $updateTicket);
	// }

	// public function updateExpiryDate($ticketId) {	
	// 	$date = new DateTime();
	// 	$dateTimestamp = $date->getTimestamp();

	// 	$dateTime = date('Y-m-d H:i:s',$dateTimestamp);
	// 	$exprDate = date('Y-m-d H:i:s', strtotime('+1 day', $dateTimestamp));

	// 	$updateTicket = "UPDATE Tickets SET expiry_date='".$exprDate."',modifiedDate='".$dateTime."'
	// 		WHERE idTickets = ".$ticketId."";					
	// 	mysqli_query($this->dbConnect, $updateTicket);
	// }
}