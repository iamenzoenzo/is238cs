<?php

require '/classes/Tickets.php';
$tickets = new Tickets;

class TicketManager {

	public function __construct(){
        $this->dbConnect = $this->dbConnect();
    }

	public function manageTickets(){
		echo $_POST['action'];

		if(!empty($_POST['action']) && $_POST['action'] == 'auth') {
			$users->login();
		}
		if(!empty($_POST['action']) && $_POST['action'] == 'listTicket') {
			echo 'yow';
			$tickets->showTickets();
		}
		if(!empty($_POST['action']) && $_POST['action'] == 'createTicket') {
			$tickets->createTicket();
		}
		if(!empty($_POST['action']) && $_POST['action'] == 'getTicketDetails') {
			return $tickets->getTicketDetails($_POST['id']);

		}
		if(!empty($_POST['action']) && $_POST['action'] == 'updateTicket') {
			$tickets->updateTicket();
		}
		if(!empty($_POST['action']) && $_POST['action'] == 'closeTicket') {
			$tickets->closeTicket();
		}
		if(!empty($_POST['action']) && $_POST['action'] == 'saveTicketReplies') {
			$tickets->saveTicketReplies();
		}
	}
}



?>
