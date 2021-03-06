<?php
include 'helpdesk/init.php';
date_default_timezone_set('Asia/Manila'); 

/* Test JSON
$json_data = '{
    "inboundSMSMessageList": {
      "inboundSMSMessage": [
        {
          "dateTime": "Sun Nov 17 2019 08:21:05 GMT+0000 (UTC)",
          "destinationAddress": "tel:21588207",
          "messageId": "5dd102f145526e546defac59",
          "message": "the last part of a reply message ",
          "resourceURL": null,
          "senderAddress": "tel:+639363139273",
          "multipartRefId": "002",
          "multipartSeqNum": "2"
        }
      ],
      "numberOfMessagesInThisBatch": 2,
      "resourceURL": null,
      "totalNumberOfPendingMessages": 0
    }
  }';
*/

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

$access_token = $subs->getAccessTokenByMobileNumber($MobileNo);

$randomTicketRef = strtoupper(substr(md5(microtime()),rand(0,26),5));

$autoReplyMessageText="Thank you for contacting TeamLaban's PLEMA. Your helpdesk reference is ".$randomTicketRef.
            ". To follow up or reply use the following format: PLEMA-REPLY ".$randomTicketRef.
            "<SPACE> Followed by your message.";

$autoReplyInvalidTicket = "Invalid Ticket ID. Please make sure that you are replying to a correct ticket ID using the same mobile number.";

if(isset($messId)){
    //Save the message
    $inbound->saveMessages($dateTime,$destinationAddress,$messId,$message,$resourceURL,$senderAddress,$numberOfMessagesInThisBatch,$totalNumberOfPendingMessages,$multipartRefId,$multipartSeqNum,0);

    //Check if not multipart message
    if($numberOfMessagesInThisBatch==1){
        
        //check if this message is a reply
        if($inbound->isReply($message,$keywords_list)){
            
            //get ticket reference
            $ticketRef = $inbound->getReplyTicketReference($message);
                                 
            if($inbound->validTicketReference($MobileNo,$ticketRef)){
                //save to replies table.
                $user_message = $inbound->getReplyMessage($message);

                $date = date_format(date_create($dateTime),"Y/m/d H:i:s");
                
                //get TicketId
                $ticketId = $inbound->getTicketIdByByReference($ticketRef,$MobileNo);
                
                //save to replies
                $inbound->saveToReplies($ticketId,$user_message,$date);
            }else{
                //Reply to user and remind to use valid ticket reference.
                $outbound->sendSms($api_short_code,$access_token,$MobileNo,$autoReplyInvalidTicket);
            }

        }else{
            //not a reply. Save to tickets table
            $ticketId = $inbound->saveToTickets($MobileNo,$message,'Open',$randomTicketRef);                 

            if($ticketId!=0){
                
                //Delete message by id
                $inbound->deleteMessagesByMessageId($messId);
                
                //auto-reply to subscriber
                $outbound->sendSms($api_short_code,$access_token,$MobileNo,$autoReplyMessageText);
            }
        }
    }else{
        
        //check if all messages were received, else do nothing
        $allMessagesReceived = $inbound->checkIfAllMessagesReceived($multipartRefId,$numberOfMessagesInThisBatch);
        
        if($allMessagesReceived==1){
            
            //get all messages and concat it.
            $message="";
            $messages = $inbound->getMessagesByMultipartReferenceId($multipartRefId);
            
            foreach($messages as $mess){
                $message=$message.$mess['message'];
            }

            //check message if reply
            if($inbound->isReply($message,$keywords_list)){
                //get ticket reference
                $ticketRef = $inbound->getReplyTicketReference($message);
                                    
                if($inbound->validTicketReference($MobileNo,$ticketRef)){
                    //save to replies table.
                    $user_message = $inbound->getReplyMessage($message);

                    $date = date_format(date_create($dateTime),"Y/m/d H:i:s");
                    
                    //get TicketId
                    $ticketId = $inbound->getTicketIdByByReference($ticketRef,$MobileNo);
                    
                    //save to replies
                    $inbound->saveToReplies($ticketId,$user_message,$date);

                    //Delete message by multiPartId
                    $inbound->deleteMessagesByMultipartRefId($multipartRefId);
                }else{
                    //Reply to user and remind to use valid ticket reference.
                    $outbound->sendSms($api_short_code,$access_token,$MobileNo,$autoReplyInvalidTicket);
                }
            }else{
                //not a reply. Save to tickets table
                $ticketId = $inbound->saveToTickets($MobileNo,$message,'Open',$randomTicketRef);                 

                if($ticketId!=0){
                    //Delete message by multiPartId
                    $inbound->deleteMessagesByMultipartRefId($multipartRefId);
                    //auto-reply to subscriber
                    $outbound->sendSms($api_short_code,$access_token,$MobileNo,$autoReplyMessageText);
                }
            }                       
                        
        }else{
            echo "still have pending messages";
        }

    }
}else{
    echo "no messages";
}


?>
