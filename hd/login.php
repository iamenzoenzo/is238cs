<?php
include 'init.php';
if($users->isLoggedIn()) {
	header('Location: ./');
}
$errorMessage = $users->login();
include('inc/header.php');
?>



<title>PLEMA - Philippine Local eMergency App</title>

<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<div class="container contact" style="margin-top: 10%">

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading" style="background:#00796B;color:white;">
					<div class="panel-title display-4">User Login</div> 
				</div>
				<div style="padding-top:30px" class="panel-body" >
					<?php if ($errorMessage != '') { ?>
						<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $errorMessage; ?></div>
					<?php } ?>
					<form id="loginform" class="form-horizontal " role="form" method="POST" action="">
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon" style = "padding-right:25px;"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" class="form-control" id="email" name="email" placeholder="Email Address" style="background:white;" required>
						</div>
						<div style="margin-bottom: 20px" class="input-group">
							<span class="input-group-addon" style = "padding-right:25px;"><i class="glyphicon glyphicon-lock"></i></span>
							<input type="password" class="form-control" id="password" name="password"placeholder="Password" required>
						</div>
						
						<div class="row justify-content-end">
							<div class="lead" style="margin-bottom:0px;"><a href="https://www.plema.digital" class="navbar-brand" style="padding-top:12px; padding-right:0px; margin-right:0px;">Home</a></div>
							<div style="margin-right:15px; margin-left:15px; padding:0px;margin-bottom:0px" class="form-group">
								<div class="controls" style="margin-bottom:0px;">
									<input type="submit" name="login" value="Login" class="btn btn-success btn-primary btn-lg" style="padding-right:20px; padding-left:20px; margin-bottom:0px;">
								</div>
							</div>
						</div>	
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row justify-content-center">	
	<div style="margin-top:10px" class="form-group">
		<div class="col-sm-12 controls">
			Admin: admin@webdamn.com // password:123	<br>
			User: smith@webdamn.com // 	password:123
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<?php include('inc/footer.php');?>
