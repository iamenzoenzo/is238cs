<?php 
include 'hd/init.php'; 

//FOR TESTING: Comment in final code

$json_data = '{"inboundSMSMessageList":
                {"inboundSMSMessage":[
                    {
                        "dateTime":"Thu Nov 14 2019 14:04:09 GMT+0000 (UTC)",
                        "destinationAddress":"tel:21580472",
                        "messageId":"5dcd5ed906698f02f18b8052",
                        "message":" this FREE renewal now by signing up through this link: bit.ly/SMACrenew. T&Cs apply. DTI19379S2019",
                        "resourceURL":null,
                        "senderAddress":"tel:+639363139273",
                        "multipartRefId":"121212",
                        "multipartSeqNum":"2"
                    }],
                    "numberOfMessagesInThisBatch":"2",
                    "resourceURL":null,
                    "totalNumberOfPendingMessages":0
                }
            }';



//FINAL CODE: Uncomment in final code
//get POST data from globelabs API via file_get_contents method
//$json_data = file_get_contents('php://input');

//decode json data into array
$jason_arr = json_decode($json_data,true);
/**  ------------------------------------------------------------------------------------------ */
//set variable for the first index
$InboundMessageList = $jason_arr['inboundSMSMessageList'];
//set variable for the message array
$InboundMessage = $InboundMessageList['inboundSMSMessage'][0];
//declare all variables and get all key values
$numberOfMessagesInThisBatch = $InboundMessageList['numberOfMessagesInThisBatch'];
$ResourceURL = $InboundMessageList['resourceURL'];
$totalNumberOfPendingMessages = $InboundMessageList['totalNumberOfPendingMessages'];
$dateTime = $InboundMessage['dateTime'];
$destinationAddress = $InboundMessage['destinationAddress'];
$messageId = $InboundMessage['messageId'];
$message = $InboundMessage['message'];
$resourceURL = $InboundMessage['resourceURL'];
$senderAddress = $InboundMessage['senderAddress'];
$multipartRefId = $InboundMessage['multipartRefId'];
$multipartSeqNum = $InboundMessage['multipartSeqNum'];
/*------------------------------------------------------------------------------------------------*/

//Save the message
$messageDbId = $inbound->saveMessages($dateTime,$destinationAddress,$messageID,$message,$resourceURL,$senderAddress,$numberOfMessagesInThisBatch,$totalNumberOfPendingMessages,$multipartRefId,$multipartSeqNum,0);

//Check if multipart message, if YES, proceed to STEP 3
if($numberOfMessagesInThisBatch==1){
    //Save to tickets table

    //Delete message by id
    //$inbound->deleteMessagesById($messageDbId);
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
?>
