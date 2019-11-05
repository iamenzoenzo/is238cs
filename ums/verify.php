<?php 
include('class/User.php');
$user = new User();
$isVerified =  $user->verifyRegister();
include('include/header.php');
?>
<title>PLeMA - Philippine Local eMergency App User Management</title>
<?php include('include/container.php');?>
<div class="container contact">	
	<h2>PLeMA - Philippine Local eMergency App User Management</h2>	
	<div class="alert alert-success col-sm-12" >
		<?php if ($isVerified) { ?>
			Your registration verified successfuly. You can <a href="login.php">login</a> to access your account.
		<?php } else { ?>
			Invalid request.
		<?php } ?>
	</div>	
</div>
<?php include('include/footer.php');?>