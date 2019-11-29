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

		$sqlWhere = "";

		$sqlQuery = "
			SELECT 
				t.idTickets as ticket_id,
				t.TicketReference as ticket_reference,
				t.MobileNumber as ticket_author,
				t.message as ticket_message,
				t.assignedTo as ticket_assignee,
				t.createDate as ticket_creation,
				t.status as ticket_status
			FROM teamlaban.".$this->ticketTable." t". 
			$sqlWhere;

		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);

		$ticketData = array();	
		
		while( $ticket = mysqli_fetch_assoc($result) ) {		
			$ticketRows = array();			
			$status = '';
			if($ticket['ticket_status'] == 'Open')	{
				$status = '<span class="label label-warning">Open</span>';
			} else if($ticket['ticket_status'] == 'Closed') {
				$status = '<span class="label label-danger">Closed</span>';
			} else if($ticket['ticket_status'] == 'Resolved') {
				$status = '<span class="label label-success">Resolved</span>';
			}	
			$title = $ticket['ticket_reference'];
			$ticketRows[] = $ticket['ticket_id'];
			$ticketRows[] = $ticket['ticket_reference'];
			$ticketRows[] = $ticket['ticket_message'];
			$ticketRows[] = $ticket['ticket_author'];
			$ticketRows[] = $ticket['ticket_creation']; 			
			$ticketRows[] = $status;
			$ticketRows[] = $ticket['ticket_assignee']; 			
			$ticketRows[] = '<a href="../users/ticket_view.php?id='.$ticket['ticket_id'].'" class="btn btn-success btn-xs view-action-btn"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>'.' '.
							'<a href="../users/ticket_details.php?id='.$ticket['ticket_id'].'" class="btn btn-warning btn-xs update-action-btn"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>';
			
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
	
	public function createTicket() {      
		// Create new ticket using subscriber info and combined message
		// should use subscriber info as parameter
		// retrieve messages from subscriber
		// merge messages into ticket message
		if(!empty($_POST['subscriberinfo']) && !empty($_POST['subscribermessage'])) {                
			
			$date = new DateTime();
			$date = $date->getTimestamp();
			$uniqid = uniqid(); 
			
			$message = "";              
			$queryInsert = "
				INSERT INTO ".$this->ticketTable." (idTickets, TicketReference, MobileNumber, message, assignedTo, createDate, modifiedDate, Status)
				VALUES ('".$ticket_id."', '".$ticket_reference."', '".$ticket_author."', '".$ticket_message."', '".$ticket_assignee."', '".$ticket_date."', '".$ticket_modified_date."','".$ticket_status."')";			
			
			mysqli_query($this->dbConnect, $queryInsert);			
			echo 'success ' . $uniqid;
		}
	}
	
	public function updateTicketInfo($ticketId,$ticketInfo,$infoType){
		// if(isset($_SESSION["admin"])) {
		// 	$updateField = "admin_read = '1'";
		// } else {
		// 	$updateField = "user_read = '1'";
		// }
		
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

	// public function showTickets(){
	// 	$sqlWhere = '';	
	// 	if(!isset($_SESSION["admin"])) {
	// 		$sqlWhere .= " WHERE t.user = '".$_SESSION["userid"]."' ";
	// 		if(!empty($_POST["search"]["value"])){
	// 			$sqlWhere .= " and ";
	// 		}
	// 	} else if(isset($_SESSION["admin"]) && !empty($_POST["search"]["value"])) {
	// 		$sqlWhere .= " WHERE ";
	// 	} 		
	// 	$time = new time;  			 
	// 	$sqlQuery = "SELECT t.id, t.uniqid, t.title, t.init_msg as message, t.date, t.last_reply, t.resolved, u.nick_name as creater, d.name as department, u.user_group, t.user, t.user_read, t.admin_read
	// 		FROM hd_tickets t 
	// 		LEFT JOIN hd_users u ON t.user = u.id 
	// 		LEFT JOIN hd_departments d ON t.department = d.id $sqlWhere ";
	// 	if(!empty($_POST["search"]["value"])){
	// 		$sqlQuery .= ' (uniqid LIKE "%'.$_POST["search"]["value"].'%" ';					
	// 		$sqlQuery .= ' OR title LIKE "%'.$_POST["search"]["value"].'%" ';
	// 		$sqlQuery .= ' OR resolved LIKE "%'.$_POST["search"]["value"].'%" ';
	// 		$sqlQuery .= ' OR last_reply LIKE "%'.$_POST["search"]["value"].'%") ';			
	// 	}
	// 	if(!empty($_POST["order"])){
	// 		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	// 	} else {
	// 		$sqlQuery .= 'ORDER BY t.id DESC ';
	// 	}
	// 	if($_POST["length"] != -1){
	// 		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	// 	}	
	// 	$result = mysqli_query($this->dbConnect, $sqlQuery);
	// 	$numRows = mysqli_num_rows($result);
	// 	$ticketData = array();	
	// 	while( $ticket = mysqli_fetch_assoc($result) ) {		
	// 		$ticketRows = array();			
	// 		$status = '';
	// 		if($ticket['resolved'] == 0)	{
	// 			$status = '<span class="label label-success">Open</span>';
	// 		} else if($ticket['resolved'] == 1) {
	// 			$status = '<span class="label label-danger">Closed</span>';
	// 		}	
	// 		$title = $ticket['title'];
	// 		if((isset($_SESSION["admin"]) && !$ticket['admin_read'] && $ticket['last_reply'] != $_SESSION["userid"]) || (!isset($_SESSION["admin"]) && !$ticket['user_read'] && $ticket['last_reply'] != $ticket['user'])) {
	// 			$title = $this->getRepliedTitle($ticket['title']);			
	// 		}
	// 		$disbaled = '';
	// 		if(!isset($_SESSION["admin"])) {
	// 			$disbaled = 'disabled';
	// 		}			
	// 		$ticketRows[] = $ticket['id'];
	// 		$ticketRows[] = $ticket['uniqid'];
	// 		$ticketRows[] = $title;
	// 		$ticketRows[] = $ticket['department'];
	// 		$ticketRows[] = $ticket['creater']; 			
	// 		$ticketRows[] = $time->ago($ticket['date']);
	// 		$ticketRows[] = $status;
	// 		$ticketRows[] = '<a href="ticket.php?id='.$ticket["uniqid"].'" class="btn btn-success btn-xs update">View Ticket</a>';	
	// 		$ticketRows[] = '<button type="button" name="update" id="'.$ticket["id"].'" class="btn btn-warning btn-xs update" '.$disbaled.'>Edit</button>';
	// 		$ticketRows[] = '<button type="button" name="delete" id="'.$ticket["id"].'" class="btn btn-danger btn-xs delete"  '.$disbaled.'>Close</button>';
	// 		$ticketData[] = $ticketRows;
	// 	}
	// 	$output = array(
	// 		"draw"				=>	intval($_POST["draw"]),
	// 		"recordsTotal"  	=>  $numRows,
	// 		"recordsFiltered" 	=> 	$numRows,
	// 		"data"    			=> 	$ticketData
	// 	);
	// 	echo json_encode($output);
	// }	
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