<?php

class InboundSms extends Database {  
 	private $messagesTable = 'Subscriber_Messages';
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
	" WHERE  multipartRefId='".$multipartRefId."'";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	$count = mysqli_num_rows($result);	
	if($count=$numberOfMessageInThisBatch){
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

public function deleteMessagesById($messageDbId){
    $sqlQuery = "
    UPDATE ".$this->messagesTable." 
    SET isDeleted = 1 WHERE id = ".$messageDbId.";";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "success";
    
}



}