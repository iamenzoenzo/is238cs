<?php

class InboundSms extends Database {  
 private $subscribersTable = 'Subscriber_Messages';
	private $dbConnect = false;
 
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
 
public function saveMessages($dateTime,$destinationAddress,$messageID,$message,$resourceURL,$senderAddress,$numberOfMessageInThisBatch,$totalNumberOfPendingMsg,$multipartRefId,$multipartSeqNum,$isDeleted) {
		
			//$date = new DateTime();
			//$date = $date->getTimestamp();
			$sqlQuery = "INSERT INTO ".$this->subscribersTable." (dateTime,destinationAddress,messageID,message,resourceURL,senderAddress,numberOfMessageInThisBatch,totalNumberOfPendingMsg,multipartRefId,multipartSeqNum,isDeleted) VALUES('".$dateTime."', '".$destinationAddress."', '".$messageID."', '".$message."', '".$resourceURL."', '".$senderAddress."', ".$numberOfMessageInThisBatch.", ".$totalNumberOfPendingMsg.", '".$multipartRefId."', ".$multipartSeqNum.", ".$isDeleted.")";
			$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}

 

}