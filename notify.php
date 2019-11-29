<?php
include 'helpdesk/init.php';
date_default_timezone_set('Asia/Manila'); 

/*
$json_data = '{
    "inboundSMSMessageList": {
      "inboundSMSMessage": [
        {
          "dateTime": "Sun Nov 17 2019 08:21:05 GMT+0000 (UTC)",
          "destinationAddress": "tel:21588207",
          "messageId": "5dd102f145526e546defac59",
          "message": "this is the message",
          "resourceURL": null,
          "senderAddress": "tel:+639363139273",
          "multipartRefId": "00000xx1xxx",
          "multipartSeqNum": "1"
        }
      ],
      "numberOfMessagesInThisBatch": 1,
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

$autoReplyMessageText="Thank you for contacting #TeamLaban's PLEMA. Our helpdesks officers will attend to you soon.";

if(isset($messId)){
    //Save the message
    $inbound->saveMessages($dateTime,$destinationAddress,$messId,$message,$resourceURL,$senderAddress,$numberOfMessagesInThisBatch,$totalNumberOfPendingMessages,$multipartRefId,$multipartSeqNum,0);

    //Check if not multipart message
    if($numberOfMessagesInThisBatch==1){

        if($inbound->hasKeyword($message,$keywords_list)){
    
            $key = explode(' ', $message,4);
        
            //PLEMA-HELP
            if($key[0]=="PLEMA-HELP"){                
                $autoReplyMessageText="Thanks for contacting PLEMA. To get all emergency phone numbers of a place,
                  text PLEMA-GET SPACE CITY CODE. Example: \"PLEMA-GET MNL\". Here are available city codes you can use: ";
                
                $cc = $phonenumber->getCityCodes();
                $allCityCodes="";
                foreach($cc as $c){
                    $allCityCodes=$allCityCodes.$c['city_code'].",";
                }
                $batch1 = $autoReplyMessageText." ".substr($allCityCodes,0,strlen($allCityCodes)-1);
                
                $ac = $phonenumber->getAgencyCodes();
                $allAgencyCodes="";
                foreach($ac as $a){
                    $allAgencyCodes=$allAgencyCodes.$a['agency_code'].",";
                }
                echo $batch1.". To get specific phone number of government agencies, text
                PLEMA-GET<SPACE>CITY CODE SPACE AGENCY CODE. Example: \"PLEMA-GET MNL PNP\". Here are available agency codes you can use
                ".substr($allAgencyCodes,0,strlen($allAgencyCodes)-1)."."; 
                
            }
            else{
                //PLEMA-GET
                if($phonenumber->validCityCode($key[1])){               
                    $phones=""; 
                    if($phonenumber->validAgencyCode($key[2])){            
                        $AgencyPhones = $phonenumber->getPhoneNumberByAgencyCode($key[1],$key[2]);
                        foreach($AgencyPhones as $pn){
                            $phones=$phones.$pn['phone_number'].",";
                        }
                        echo "Thanks for contacting PLEMA. Here are the emergency phone numbers of "
                        .$key[1]." ".$key[2].": ".substr($phones,0,strlen($phones)-1).".";
                    }else{
                        $AgencyPhones = $phonenumber->getPhoneNumberByCityCode($key[1]);
                        foreach($AgencyPhones as $pn){
                            $phones=$phones.$pn['agency_code'].": ".$pn['phone_number'].",";
                        }
                        echo "Thanks for contacting PLEMA. Here are the emergency phone numbers of "
                        .$phonenumber->getCityNameByCityCode($key[1]).". ".substr($phones,0,strlen($phones)-1).".";
                    }
                }
            }
        }else{
            
            // No keyword
            $inbound->saveToTickets($MobileNo,$message,'Open');
            //Delete message by id
            $inbound->deleteMessagesByMessageId($messId);                
            //auto-reply to subscriber
            $outbound->sendSms($api_short_code,$access_token,$MobileNo,$autoReplyMessageText);

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

            //Save to tickets table
            $inbound->saveToTickets($MobileNo,$message,'Open');
            //Delete message by multiPartId
            $inbound->deleteMessagesByMultipartRefId($multipartRefId);
            //auto-reply to subscriber
            $outbound->sendSms($api_short_code,$access_token,$MobileNo,$autoReplyMessageText);                 
                        
        }else{
            echo "still have pending messages";
        }

    }
}else{
    echo "no messages";
}


?>
