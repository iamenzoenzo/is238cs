<?php

class InboundSms extends Database {
	private $messagesTable = 'Subscriber_Messages';
	private $ticketsTable = 'Tickets';
	private $repliesTable = 'Ticket_replies';
	private $dbConnect = false;

	public function __construct(){
        $this->dbConnect = $this->dbConnect();
	}

public function saveMessages($dateTime,$destinationAddress,$messageID,$message,$resourceURL,$senderAddress,$numberOfMessageInThisBatch,$totalNumberOfPendingMsg,$multipartRefId,$multipartSeqNum,$isDeleted) {
	//final code
	$sqlQuery = "INSERT INTO ".$this->messagesTable." (dateTime,destinationAddress,messageID,message,resourceURL,senderAddress,numberOfMessageInThisBatch,totalNumberOfPendingMsg,multipartRefId,multipartSeqNum,isDeleted) VALUES('".$dateTime."', '".$destinationAddress."', '".$messageID."', '".$message."', '".$resourceURL."', '".$senderAddress."', ".$numberOfMessageInThisBatch.", ".$totalNumberOfPendingMsg.", '".$multipartRefId."', ".$multipartSeqNum.", ".$isDeleted.")";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	return $result;
}

public function getMessagesByMultipartReferenceId($multipartRefId){
	$sqlQuery = "SELECT * FROM ".$this->messagesTable.
	" WHERE  multipartRefId='".$multipartRefId."'  ORDER BY multipartSeqNum ASC";
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
	if($count=$numberOfMessageInThisBatch){
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
    SET isDeleted = 1 WHERE multipartRefId = ".$multipartRefId.";";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "success";
}

public function deleteMessagesByDbId($messageDbId){
    $sqlQuery = "
    UPDATE ".$this->messagesTable."
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

public function saveToTickets($MobileNumber,$message,$Status,$ticketRef) {

	$sqlQuery = "INSERT INTO ".$this->ticketsTable." (TicketReference,MobileNumber,message,Status)
	VALUES('".$ticketRef."', '".$MobileNumber."','".$message."', '".$Status."');";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	return $result;
}

public function saveToReplies($ticketId,$message,$datereplied) {
	$sqlQuery = "INSERT INTO ".$this->repliesTable." (ticket_id,Reply,Date)
	VALUES(".$ticketId.",'".$message."', '".$datereplied."');";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	return $result;
}

public function getTicketIdByByReference($TicketReference,$MobileNumber){
    //final code
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

}
