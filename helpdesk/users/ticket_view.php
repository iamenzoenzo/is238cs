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

<div class="container" style="margin-top:2%">
	<section class="comment-list">

		<div class="replies" id="replies">
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
		</div>
</div>

<script>


</script>
