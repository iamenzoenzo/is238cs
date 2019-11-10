<?php 
include('class/User.php');
$user = new User();
$user->loginStatus();
include('../include/header.php');
?>
<title>PLeMA - Philippine Local eMergency App User Management</title>
<?php include('include/container.php');?>
<div class="container contact">	
	<h2>PLeMA User Management</h2>	
	<?php include('menu.php');?>
	<div class="table-responsive">	
	You're welcome!
	</div>
</div>	
<?php include('../include/footer.php');?>