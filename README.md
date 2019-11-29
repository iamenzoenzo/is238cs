# TeamLaban

# Functions

>Send SMS function. Change only **$recipient_mobile_number** and **$message**
```$outbound->sendSms($short_code, $access_token,$recipient_mobile_number,$message);```

>Assign agent function will set all Open Status to In Progress and update expiry date to 24 hours after current date time
```$outbound->assignAgent($MobileNumber,$AgentName);```

>This function will query all mobile numbers with expired messages and set the status of all the messages of that mobile number to Open and assignedTo to null and expiry_date to null
```$outbound->returnExpiredTickets();```

>This functions retrieves the access_token of a mobile number
```$subs->getAccessTokenByMobileNumber($mobileNumber);```
