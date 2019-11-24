<?php

ini_set("allow_url_fopen", 1);
if(isset($_SESSION)){session_destroy();}
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
$hooks =  getMyHooks();
includeHook($hooks,'pre');
if($settings->twofa == 1){
  $google2fa = new PragmaRX\Google2FA\Google2FA();
}

require '../users/Tickets.php';
$tickets = new Tickets;

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
								<span class="ticket-title"><?php echo $ticketDetails['ticket_title']; ?></span>
							</div>
							<div class="col-md-2 col-sm-2"> 
								<div class="dropdown">
									<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Update Status
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuBtn">
										<a class="dropdown-item" href="#">Open</a>
										<a class="dropdown-item" href="#">Resolved</a>
										<a class="dropdown-item" href="#">Close</a>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="card-body panel-body">
						<div class="comment-post card-text" style="margin-top:20px; margin-bottom:20px;">
							<p><?php echo $ticketDetails['ticket_message']; ?></p>
						</div>
					</div>
					<div class="card-footer panel-heading right">
						<span class="glyphicon glyphicon-time"></span> <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> <?php echo $ticketDetails['ticket_created']; ?></time>
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

		<form method="post" id="ticketReply">
			<article class="row justify-content-center" style="margin-top: 10px;">
				<div class="col-md-10 col-sm-10">
					<div class="form-group">
						<textarea class="form-control" rows="5" id="message" name="message" placeholder="Enter your reply..." required></textarea>
					</div>
			</div>
			</article>
			<article class="row justify-content-center">
				<div class="col-md-10 col-sm-10">
					<div class="form-group">
						<input type="submit" name="reply" id="replyBtn" class="btn btn-success" value="Reply" />
					</div>
				</div>
			</article>
			<input type="hidden" name="ticketId" id="ticketId" value="<?php echo $ticketDetails['ticket_id']; ?>" />
			<input type="hidden" name="action" id="action" value="saveTicketReplies" />
		</form>
	</section>
</div>


<script>

	$('#replyBtn').click(function(){
		// event.preventDefault();

		console.log('new reply created');
		var newReply = $('#ticketReply').serializeArray();
		console.log(newReply);

		$.ajax({
			type: "POST",
			url: '../users/Tickets/saveTicketReplies', 
			data: {
				action:newReply['action'],
				id:newReply['ticketId'],
				message:newReply['message'],
				reply:newReply['reply']
			},
			success: function (data) {
				console.log('success');
				alert(data);
			}
		});
	})
</script>