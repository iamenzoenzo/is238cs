<?php

ini_set("allow_url_fopen", 1);
if(isset($_SESSION)){session_destroy();}
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
$hooks =  getMyHooks();
includeHook($hooks,'pre');
if($settings->twofa == 1){
  $google2fa = new PragmaRX\Google2FA\Google2FA();

print('id::'+$id);
}

// require '../users/TicketManager.php';
// $ticketInfo = new TicketManager();

$ticketDetails = [
	'ticket_id' => 34,
	'ticket_status'=>'open',
	'ticket_title' => 'Ticket Reference Here',
	'ticket_message' => 'Ticket Message Here',
	'ticket_created' => date('M/D/Y')
];
$ticketReplies = [
	['reply_author' =>'Assignee Here', 'reply_date' => date('M/D/Y'), 'reply_message' => 'Reply 1 Here'],
	['reply_author' =>'Assignee Here', 'reply_date' => date('M/D/Y'), 'reply_message' => 'Reply 2 Here']
];
?>


<title>PLEMA Helpdesk</title>

<div class="container" style="margin-top:2%">
	<section class="comment-list">
		<article class="row justify-content-center">
			<div class="col-md-10 col-sm-10" style="padding:0px;">
				<div class="card panel panel-default arrow left">
					<div class="card-header panel-heading right">
						<div class="row">
							<?php if($ticketDetails['ticket_status'] == 'closed') { ?>
							<div class="col-md-1 col-sm-1">
								<button type="button" class="btn btn-danger btn-sm">
								Closed
								</button>
							</div>
							<?php } else if($ticketDetails['ticket_status'] == 'open'){ ?>
							<div class="col-md-1 col-sm-1">
								<button type="button" class="btn btn-warning btn-sm">
								Open
								</button>
							</div>
							<?php } else if($ticketDetails['ticket_status'] == 'resolved'){ ?>
							<div class="col-md-1 col-sm-1">
								<button type="button" class="btn btn-success btn-sm">
								Open
								</button>
							</div>
							<?php } ?>
							<div class="col-md-9 col-sm-9" style="padding-top:5px;">
								<span><h5 class="ticket-title" id="ticket-title"></h5></span>
							</div>
						</div>
						
					</div>
					<div class="card-body panel-body">
						<div class="comment-post card-text" style="margin-top:20px; margin-bottom:20px;">
							<p class="ticket-message" id="ticket-message"></p>
						</div>
					</div>
					<div class="card-footer panel-heading right">
						<span class="glyphicon glyphicon-time"></span> 
						<time class="comment-date ticket-created" id="ticket-created" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i></time>
						&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> 
						&nbsp;&nbsp;<span class="glyphicon glyphicon-briefcase"></span>
					</div>
				</div>
			</div>
		</article>

		<?php foreach ($ticketReplies as $replies) { ?>
			<article class="row justify-content-center">
				<div class="col-md-10 col-sm-10" style="margin-top:10px;">
					<div class="card panel panel-default arrow right">
						<div class="card-header panel-heading">
							<div class = "row">
								<div class="col-md-9 col-sm-9">
									<span class="glyphicon glyphicon-user"></span> <?php echo $replies['reply_author']; ?>
								</div>
								<div class="col-md-3 col-sm-3">
									<span class="glyphicon glyphicon-time"></span> <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> <?php echo $replies['reply_date']; ?></time>
								</div>	
							</div>
						</div>
						<div class="card-body panel-body">
							<div class=" card-text comment-post">
							<p>
							<?php echo $replies['reply_message']; ?>
							</p>
							</div>
						</div>

					</div>
				</div>
			</article>
		<?php } ?>
</div>

<script>
	$(document).ready(function() { 
		
		$.ajax({
			url:'../users/TicketManager.php',
			type:'POST',
			dataType:'json',
			data:{
				action:'viewTicketInfo',
				id: 34
			},
			success: function(data){
				document.getElementById("ticket-title").innerHTML = data['ticket_reference'];
				document.getElementById("ticket-message").innerHTML = data['ticket_message'];
				document.getElementById("ticket-created").innerHTML = data['ticket_creation'];
				console.log('success');
			},
			error: function(data){

			}
		});
		
	});
</script>