<?php

class Tickets{

    private $ticketTable = 'Tickets';
	private $ticketRepliesTable = 'Ticket_replies';
	private $dbConnect = false;

	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
	public function showTickets(){
		$sqlWhere = '';	
		if(!isset($_SESSION["admin"])) {
			$sqlWhere .= " WHERE t.assignedTo = '".$_SESSION["userid"]."' ";
			
			if(!empty($_POST["search"]["value"])){
				$sqlWhere .= " and ";
			}
		} 
		else if(isset($_SESSION["admin"]) && !empty($_POST["search"]["value"])) {
			$sqlWhere .= " WHERE ";
		} 		
		
		$time = new time;  			 
		$sqlQuery = "
			SELECT 
				t.idTickets as ticket_id,
				t.TicketReference as ticket_reference,
				t.MobileNumber as ticket_author,
				t.message as ticket_message,
				t.assignedTo as ticket_assignee,
				t.createDate as ticket_creation,
				t.status as ticket_status
			FROM ".$this->ticketTable." t". 
			$sqlWhere;
		
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= ' (t.idTickets LIKE "%'.$_POST["search"]["value"].'%" ';					
			$sqlQuery .= ' OR t.TicketReference LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR t.message LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR t.status LIKE "%'.$_POST["search"]["value"].'%") ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY t.idTickets DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$ticketData = array();	
		while( $ticket = mysqli_fetch_assoc($result) ) {		
			$ticketRows = array();			
			$status = '';
			if($ticket['ticket_status'] != 'resolved')	{
				$status = '<span class="label label-success">Open</span>';
			} else if($ticket['ticket_status'] == 'resolved') {
				$status = '<span class="label label-danger">Closed</span>';
			}	
			$title = $ticket['t.TicketReference'];
			// if((isset($_SESSION["admin"]) && !$ticket['admin_read'] && $ticket['last_reply'] != $_SESSION["userid"]) || (!isset($_SESSION["admin"]) && !$ticket['user_read'] && $ticket['last_reply'] != $ticket['user'])) {
			// 	$title = $this->getRepliedTitle($ticket['title']);			
			// }
			$disbaled = '';
			if(!isset($_SESSION["admin"])) {
				$disbaled = 'disabled';
			}			

			

			$ticketRows[] = $ticket['ticket_id'];
			$ticketRows[] = $ticket['ticket_reference'];
			$ticketRows[] = $ticket['ticket_message'];
			$ticketRows[] = $ticket['ticket_author']; 			
			$ticketRows[] = $time->ago($ticket['ticket_creation']);
			$ticketRows[] = $ticket['ticket_status'];
			$ticketRows[] = $ticket['ticket_assignee'];
			$ticketRows[] = '';
			$ticketRows[] = '<a href="ticket_details.php?id='.$ticket["ticket_id"].'" class="btn btn-success btn-xs update"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>'.' '.
							'<button type="button" name="update" id="'.$ticket["ticket_id"].'" class="btn btn-warning btn-xs update" '.$disbaled.'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>'.' '.
							'<button type="button" name="delete" id="'.$ticket["ticket_id"].'" class="btn btn-danger btn-xs delete"  '.$disbaled.'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';	
			$ticketRows[] = '';
			$ticketRows[] = '';
			$ticketData[] = $ticketRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$ticketData
		);
		echo json_encode($output);
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
		if(isset($_SESSION["admin"])) {
			$updateField = "admin_read = '1'";
		} else {
			$updateField = "user_read = '1'";
		}
		$updateTicket = "UPDATE ".$this->ticketTable." SET ";
		$sqlWhere = "WHERE idTickets = ".$ticketId;
		
		if($infoType == 'status'){
			$ticketInfo .= " status = ".$ticketStatus." ".$sqlWhere;
		}
		if($infoType == 'assignee'){
			$ticketInfo .= " assignedTo = ".$ticketStatus." ".$sqlWhere;
		}

		mysqli_query($this->dbConnect, $updateTicket);
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
			FROM ".$this->ticketTable." t 
			WHERE t.idTickets = ".$id;	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			echo json_encode($row);       
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
	}	
	public function getTicketReplies($id) {  		
		$sqlQuery = "SELECT r.id, r.text as message, r.date, u.nick_name as creater, d.name as department, u.user_group  
			FROM ".$this->ticketRepliesTable." r
			LEFT JOIN ".$this->ticketTable." t ON r.ticket_id = t.id
			LEFT JOIN hd_users u ON r.user = u.id 
			LEFT JOIN hd_departments d ON t.department = d.id 
			WHERE r.ticket_id = '".$id."'";	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
       	$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;            
		}
        return $data;
    }
}

?>