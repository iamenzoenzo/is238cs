<?php

require '../init.php';
// require '../users/init.php';

$tickets = new Tickets();

if(!empty($_POST['action']) && $_POST['action'] == 'auth') {
	$users->login();
}
if(!empty($_POST['action']) && $_POST['action'] == 'showAllTickets') {
	$allTickets = $tickets->getAllTickets(); 
	print($allTickets);
	return $allTickets;
}
if(!empty($_POST['action']) && $_POST['action'] == 'createTicket') {
	$tickets->createTicket();
}
if(!empty($_POST['action']) && $_POST['action'] == 'viewTicketInfo') {
	$ticketInfo = $tickets->getTicketDetails($_POST['id']);
	print($ticketInfo);
	return $ticketInfo;
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateTicket') {
	// update modifier, modified date, status
	$tickets->updateTicketInfo($_POST['id'],$_POST['info'],$_POST['data_type']);
}

if(!empty($_POST['action']) && $_POST['action'] == 'saveTicketReplies') {
	$tickets->saveTicketReplies($_POST['id'],$_POST['message'],$_POST['ticket_id']);
}

?>