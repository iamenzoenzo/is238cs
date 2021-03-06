<?php
date_default_timezone_set('Asia/Manila');

class InboundSms extends Database {
	private $messagesTable = 'Subscriber_Messages';
	private $ticketsTable = 'Tickets';
	private $repliesTable = 'Ticket_replies';
	private $dbConnect = false;

	public function __construct(){
        $this->dbConnect = $this->dbConnect();
	}

public function saveMessages($dateTime,$destinationAddress,$messageID,$message,$resourceURL,$senderAddress,$numberOfMessageInThisBatch,$totalNumberOfPendingMsg,$multipartRefId,$multipartSeqNum,$isDeleted) {
	
	$sqlQuery = "INSERT INTO ".$this->messagesTable." (dateTime,destinationAddress,messageID,message,resourceURL,senderAddress,numberOfMessageInThisBatch,totalNumberOfPendingMsg,multipartRefId,multipartSeqNum,isDeleted) VALUES('".$dateTime."', '".$destinationAddress."', '".$messageID."', '".$message."', '".$resourceURL."', '".$senderAddress."', ".$numberOfMessageInThisBatch.", ".$totalNumberOfPendingMsg.", '".$multipartRefId."', '".$multipartSeqNum."', ".$isDeleted.")";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	return $result;
}

public function getMessagesByMultipartReferenceId($multipartRefId){
	$sqlQuery = "SELECT * FROM ".$this->messagesTable.
	" WHERE  multipartRefId='".$multipartRefId.
	"' AND isDeleted=0 ORDER BY multipartSeqNum ASC";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;
		}
        return $data;
}

public function checkIfAllMessagesReceived($multipartRefId,$numberOfMessageInThisBatch){
	$sqlQuery = "SELECT * FROM ".$this->messagesTable.
	" WHERE  multipartRefId='".$multipartRefId."' AND IsDeleted=0;";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	$count = mysqli_num_rows($result);
	if($count==$numberOfMessageInThisBatch){
		return true;
	}else{
		return false;
	}
}


public function checkIfThreadStatusInProgress($MobileNumber){
	$sqlQuery = "SELECT * FROM ".$this->ticketsTable.
	" WHERE  MobileNumber='".$MobileNumber."' AND Status='In Progress' AND assignedTo <> '';";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	$count = mysqli_num_rows($result);
	if($count>0){
		return true;
	}else{
		return false;
	}
}

public function validTicketReference($mobileNo,$TicketReference){
	$sqlQuery = "SELECT * FROM ".$this->ticketsTable.
	" WHERE  TicketReference='".$TicketReference."' AND MobileNumber='".$mobileNo."';";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	$count = mysqli_num_rows($result);
	if($count!=0){
		return true;
	}else{
		return false;
	}
}

public function deleteMessagesByMultipartRefId($multipartRefId){
    $sqlQuery = "
    UPDATE ".$this->messagesTable."
    SET isDeleted = 1 WHERE multipartRefId = '".$multipartRefId."';";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "Success";
}

public function deleteMessagesByDbId($messageDbId){
    $sqlQuery = "UPDATE ".$this->messagesTable."
    SET isDeleted = 1 WHERE id = ".$messageDbId.";";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "success";

}

public function deleteMessagesByMessageId($messageId){
    $sqlQuery = "
    UPDATE ".$this->messagesTable."
    SET isDeleted = 1 WHERE messageID = '".$messageId."';";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "success";

}

public function saveToTickets($MobileNumber,$message,$Status) {
	$expiry = date("Y/m/d H:i:s", strtotime('now + 1 days')); 
	$created_date = date("Y/m/d H:i:s", strtotime('now')); 

	$sqlQuery = "INSERT INTO ".$this->ticketsTable." (MobileNumber,message,Status,CreatedBy,expiry_date,createDate)
	VALUES('".$MobileNumber."','".$message."', '".$Status."','".$MobileNumber."','".$expiry."','".$created_date."');";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	return $result;
}

public function saveToReplies($ticketId,$message,$datereplied) {
	$sqlQuery = "INSERT INTO ".$this->repliesTable." (ticket_id,Reply,created)
	VALUES(".$ticketId.",'".$message."', '".$datereplied."');";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	return $result;
}

public function getTicketIdByByReference($TicketReference,$MobileNumber){
    
    $sqlQuery = "SELECT Tickets.idTickets FROM ".$this->ticketsTable.
    " WHERE TicketReference='".$TicketReference."' AND MobileNumber='".$MobileNumber."';";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $row = mysqli_fetch_array($result);
    $id = $row['idTickets'];
    if($id!=0){
        return $id;
    }
    else{
        return 0;
	}

}

public function isReply($textMessage,$keywords_list){

	$key = explode(' ', $textMessage,2);
	
    if(in_array(strtoupper($key[0]),$keywords_list)){
        return true;
    }else{
        return false;
    }
}

public function hasKeyword($textMessage,$keywords_list){

	$key = explode(' ', $textMessage,2);
	
    if(in_array(strtoupper($key[0]),$keywords_list)){
        return true;
    }else{
        return false;
    }
}

public function getReplyTicketReference($textMessage){
    $key = explode(' ', $textMessage,3);
    return strtoupper($key[1]);
}

public function getReplyMessage($textMessage){
    $key = explode(' ', $textMessage,3);
    return $key[2];
}

}
