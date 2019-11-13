<?php 
include('class/User.php');
$user = new User();
$user->loginStatus();
include('include/header.php');
?>
<div class="row" style="margin-top:5%; text-align:center; color:#ffffff; text-shadow: 2px 2px 3px #000000;">
	<h1>PLeMA Helpdesk Agent Management</h1>
</div>
<?php //include('include/container.php');?>
<div class="container contact dash-container rounded">	
	<?php include('menu.php');?>
	<div class="table-responsive">	
	You're welcome!
	</div>
</div>	
<?php include('include/footer.php');?>