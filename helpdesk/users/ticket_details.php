<?php
ini_set("allow_url_fopen", 1);
if(isset($_SESSION)){session_destroy();}
require_once 'init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
$hooks =  getMyHooks();
includeHook($hooks,'pre');
if($settings->twofa == 1){
  $google2fa = new PragmaRX\Google2FA\Google2FA();
}
include 'TicketManager.php';
$messages = $tickets->getSubscriberMessages($_GET['id']);
?>


<title>PLEMA Helpdesk</title>

<a href="dashboard.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Go back to list</a>

<div class="container" style="margin-top:2%">
	<section class="comment-list">
		<?php foreach ($messages as $index=>$value) {  ?>
			<article class="row justify-content-center">
				<div class="col-md-10 col-sm-10" style="margin-top:10px;">
					<div class="card panel panel-default arrow right">
						<div class="card-header panel-heading">
							<div class = "row">
								<div class="col-md-9 col-sm-9">
									<span class="glyphicon glyphicon-user"></span> <?php echo $value[4]; ?>
								</div>
								<div class="col-md-3 col-sm-3">
									<span class="glyphicon glyphicon-time"></span> <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> <?php echo $value[3]; ?></time>
								</div>
							</div>
						</div>
						<div class="card-body panel-body">
							<div class=" card-text comment-post">
							<p>
							<?php echo $value[2]; ?>
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
            <div class="text-center">
            <a href="dashboard.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Go back to list</a>
						<input type="submit" name="reply" id="replyBtn" class="btn btn-success" value="Reply" />
            </div>
					</div>
				</div>
			</article>
			<input type="hidden" name="ticketId" id="subscriberId" value="<?php echo $value[1]; ?>" />
			<input type="hidden" name="userId" id="userId" value="<?php echo $_SESSION['user']; ?>" />
			<input type="hidden" name="action" id="action" value="saveTicketReplies" />
		</form>



	</section>
</div>


<script>
	$('#replyBtn').click(function(){

		var formData =  $('#ticketReply').serializeArray();
		var message = formData[0]['value'];
		var subscriberId = formData[1]['value'];
		var userId = formData[2]['value'];
		var today = new Date();
		var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
		var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
		var dateTime = date+' '+time;
		console.log(message+'//'+userId);
		$.ajax({
			url:'TicketManager.php',
			type:'POST',
			data:{
				action:'saveTicketReplies',
				subscriberId: subscriberId,
				message: message,
				timestamp: dateTime,
				status: 'Closed',
				userId: userId
			},
			success: function(){
				setTimeout(function(){// wait for 5 secs(2)
					location.reload(); // then reload the page.(3)
				}, 5000);
			},
			error: function(){
			}
		});
	});
</script>
