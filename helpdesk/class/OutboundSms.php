<?php

class OutboundSms extends Database {
    private $messagesTable = 'Subscriber_Messages';
	private $ticketsTable = 'Tickets';
	private $repliesTable = 'Ticket_replies';
    private $dbConnect = false;

public function __construct(){
    $this->dbConnect = $this->dbConnect();
}

public function sendSms($short_code, $access_token,$recipient_mobile_number,$message){
    
    $clientCorrelator = strtoupper(substr(md5(microtime()),rand(0,26),10))."-".$recipient_mobile_number;
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/".$short_code."/requests?access_token=".$access_token ,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\"outboundSMSMessageRequest\": { \"clientCorrelator\": \"".$clientCorrelator."\", \"senderAddress\": \"".$short_code."\", \"outboundSMSTextMessage\": {\"message\": \"".$message."\"}, \"address\": \"".$recipient_mobile_number."\" } }",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
    ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
    return "cURL Error #:" . $err;
    } else {
    return $response;
    }
}
}
