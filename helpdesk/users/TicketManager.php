<?php

require '../init.php';
// require '../users/init.php';

// include '../Tickets.php';

$tickets = new Tickets();

if(!empty($_POST['action']) && $_POST['action'] == 'auth') {
	$users->login();
}
if(!empty($_POST['action']) && $_POST['action'] == 'showAllTickets') {
	$allTickets = $tickets->getAllTickets();
	print_r($allTickets);
	return $allTickets;
}
if(!empty($_POST['action']) && $_POST['action'] == 'createTicket') {
	$tickets->createTicket();
}
if(!empty($_POST['action']) && $_POST['action'] == 'viewTicketInfo') {
	$ticketInfo = $tickets->getTicketDetails($_POST['id']);
	$ticketReplies = $tickets->getTicketReplies($_POST['id']);

	return json_encode(['ticketInfo'=>$ticketInfo,'ticketReplies'=>$ticketReplies]);
}
if(!empty($_POST['action']) && $_POST['action'] == 'getTicketReplies') {
	$ticketReplies = $tickets->getTicketReplies($_POST['id']);
	print($ticketReplies);
	return $ticketReplies;
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateTicket') {
	// update modifier, modified date, status
	$updatedTicket = $tickets->updateTicketInfo($_POST['id'],$_POST['info'],$_POST['data_type']);
	print_r($updatedTicket);
	return $updatedTicket;
}

if(!empty($_POST['action']) && $_POST['action'] == 'saveTicketReplies') {
	$tickets->createTicket($_POST['subscriberId'],$_POST['message'],$_POST['timestamp'],$_POST['status']);
}

?>
