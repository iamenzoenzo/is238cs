<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>PLeMA - Philippine Local eMergency App</title>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<script src="js/ajax.js"></script>

<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />

<?php //include('inc/container.php');?>
<div class="row" style="margin-top:5%; text-align:center; color:#ffffff; text-shadow: 2px 2px 3px #000000;">
	<h1>PLeMA - Philippine Local eMergency App</h1>
</div>
<div class="container dash-container rounded" >	
	<div class="row home-sections">
		<?php include('menus.php'); ?>		
	</div>
	<div class="">   		
		<p>This is where you view and manage your tickets.</p>		
		<table id="listTickets" class="table table-striped table-hover table-responsive ">
			<thead class="">
				<tr>
					<th>S/N</th>
					<th>Ticket ID</th>
					<th>Subject</th>
					<th>Created By</th>					
					<th>Created</th>	
					<th>Status</th>
					<th>Assignee</th>
					<th></th>	
					<th></th>		
					<th></th>		
				</tr>
			</thead>
		</table>
	</div>
</div>	
<?php include('inc/footer.php');?>