<?php 
include 'hd/init.php'; 

$KEYWORDS = array("PLEMA-HELP","PLEMA-REPLY");
//FOR TESTING: Comment in final code

/*
$json_data = '{
    "inboundSMSMessageList": {
      "inboundSMSMessage": [
        {
          "dateTime": "Sun Nov 17 2019 08:21:05 GMT+0000 (UTC)",
          "destinationAddress": "tel:21588207",
          "messageId": "5dd102f145526e546defac59",
          "message": "PLEMA-REPLY 6CE8F This is a reply to my last message. Another test",
          "resourceURL": null,
          "senderAddress": "tel:+639271280446"
        }
      ],
      "numberOfMessagesInThisBatch": 1,
      "resourceURL": null,
      "totalNumberOfPendingMessages": 0
    }
  }';
*/

//FINAL CODE: Uncomment in final code
//get POST data from globelabs API via file_get_contents method
$json_data = file_get_contents('php://input');
//decode json data into array
$jason_arr = json_decode($json_data,true);

$InboundMessageList = $jason_arr['inboundSMSMessageList'];
$InboundMessage = $InboundMessageList['inboundSMSMessage'][0];
$numberOfMessagesInThisBatch = $InboundMessageList['numberOfMessagesInThisBatch'];
$ResourceURL = $InboundMessageList['resourceURL'];
$totalNumberOfPendingMessages = $InboundMessageList['totalNumberOfPendingMessages'];
$dateTime = $InboundMessage['dateTime'];
$destinationAddress = $InboundMessage['destinationAddress'];
$messId = $InboundMessage['messageId'];
$message = $InboundMessage['message'];
$resourceURL = $InboundMessage['resourceURL'];
$senderAddress = $InboundMessage['senderAddress'];
$MobileNo = substr($senderAddress,(strpos($senderAddress,":"))+4,strlen($senderAddress));
if($numberOfMessagesInThisBatch>1){
    $multipartRefId = $InboundMessage['multipartRefId'];
    $multipartSeqNum = $InboundMessage['multipartSeqNum'];
}else{
    $multipartRefId = '';
    $multipartSeqNum = '';
}

//-----------TESTING

if(count($jason_arr)!=0){    
    //Save the message
    $messageDbId = $inbound->saveMessages($dateTime,$destinationAddress,$messId,$message,$resourceURL,$senderAddress,$numberOfMessagesInThisBatch,$totalNumberOfPendingMessages,$multipartRefId,$multipartSeqNum,0);

    //Check if multipart message, if YES, proceed to STEP 3
    if($numberOfMessagesInThisBatch==1){
        //check if this message is a reply
        if(isReply($message,$KEYWORDS)){
            //get ticket reference
            $ticketRef = getReplyTicketReference($message);
            $validReference = $inbound->validTicketReference($MobileNo,$ticketRef);
            if($validReference){
                //save to replies table.
                $mes = getReplyMessage($message);
                $date = date_format(date_create($dateTime),"Y/m/d H:i:s");
                $ticketId = $inbound->getTicketIdByByReference($ticketRef,$MobileNo);
                $inbound->saveToReplies($ticketId,$mes,$date);
            }else{
                //Reply to user and remind to use valid ticket reference.
                echo "invalid ticket reference";
            }
            
        }else{
            //not a reply. Save to tickets table
            $ticketId = $inbound->saveToTickets($MobileNo,$message,'Open');
            if($ticketId!=0){
                //Delete message by id
                $inbound->deleteMessagesByMessageId($messId);
            }    
        }
    }else{
        //check if all messages were received, else do nothing
        $allMessagesReceived = $inbound->checkIfAllMessagesReceived($multipartRefId,$numberOfMessagesInThisBatch);
        if($allMessagesReceived=1){
            //get all messages and concat it.
            $message="";
            $messages = $inbound->getMessagesByMultipartReferenceId($multipartRefId);
            foreach($messages as $mess){
                $message=$message.$mess['message'];
            }
            //get userId by mobile number
            //Save to tickets table
            //delete the messages by multipart ref Id
            //$inbound->deleteMessagesByMultipartRefId($multipartRefId);
        }else{
            echo "still have pending messages";
        }
        
    } 
}else{
    echo "no message";
}



//FUNCTIONS
function isReply($textMessage,$keywords){
    $key = explode(' ', $textMessage,2);
    if(in_array($key[0],$keywords)){
        return true;
    }else{
        return false;
    }        
}

function getReplyTicketReference($textMessage){
    $key = explode(' ', $textMessage,3);
    return $key[1];    
}

function getReplyMessage($textMessage){
    $key = explode(' ', $textMessage,3);
    return $key[2];    
}
?>