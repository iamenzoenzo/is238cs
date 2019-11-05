<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>webdamn.com : Demo Build Helpdesk System with PHP & MySQL</title>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/ajax.js"></script>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<div class="container">	
	<div class="row home-sections">
	<h2>Example: Build Helpdesk System with PHP & MySQL</h2>	
	<?php include('menus.php'); ?>		
	</div> 
	<div class="">   		
		<p>View and manage tickets that may have responses from support team.</p>		
		<table id="listTickets" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>S/N</th>
					<th>Ticket ID</th>
					<th>Subject</th>
					<th>Department</th>
					<th>Created By</th>					
					<th>Created</th>	
					<th>Status</th>
					<th></th>
					<th></th>
					<th></th>					
				</tr>
			</thead>
		</table>
	</div>
	<?php include('add_ticket_model.php'); ?>
</div>	
<?php include('inc/footer.php');?>