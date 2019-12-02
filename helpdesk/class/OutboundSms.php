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

//assign agent to ticket, set status to In Progress and set expiry to 24 hours
public function assignAgent($MobileNumber,$AgentName){
    $expiry_date = date("Y/m/d H:i:s", strtotime('now + 1 days'));
    $sqlQuery = "UPDATE Tickets"."
    SET assignedTo = '".$AgentName."',
    Status='In Progress', expiry_date='".$expiry_date."' 
    WHERE MobileNumber = '".$MobileNumber."'
    AND Status <> 'Closed';";
    mysqli_query($this->dbConnect, $sqlQuery);
    return "success";	
}

//query mobileNo with expired tickets, update thread set assignedTo = null and expiry is null
public function returnExpiredTickets(){
    $curr = date("Y/m/d H:i:s", strtotime('now'));
    $sqlQuery = "SELECT Tickets.MobileNumber FROM Tickets".
	" WHERE  expiry_date < '".$curr.
    "' AND Status <> 'Closed' and assignedTo <> null;";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	$data= array();
 $counter=0;
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           // assignAgent($row["MobileNumber"],"");
            $sqlUpdate = "UPDATE Tickets"."
            SET assignedTo = null,
            Status='Open', expiry_date = null 
            WHERE MobileNumber = '".$row["MobileNumber"]."'
            AND Status <> 'Closed';";
            mysqli_query($this->dbConnect, $sqlUpdate);
            $counter++;
        }
    return $counter." messages had been returned to dashboard.";	
    
}


}