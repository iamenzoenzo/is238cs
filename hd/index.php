<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('../inc/header.php');
$user = $users->getUserInfo();
?>
<title>PLEMA - Philippine Local eMergency App</title>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/ajax.js"></script>
<link rel="stylesheet" href="css/style.css" />

<?php include('../inc/container.php');?>
<div class="container dash-container rounded">	
	<div class="row home-sections">
		<?php include('menus.php'); ?>		
	</div>
	<div>
	<div class="">   		
		<p>This is where you view and manage your tickets.</p>		
		<div style="margin-right:15px; margin-left:0px; margin-bottom:0px" class="form-group">
			<div class="controls col-md-6" style="margin-bottom:0px; padding-left:0px;">
				<a href="#" id="createTicket" class="btn btn-info btn-primary btn-sm" style="padding-right:20px; padding-left:20px; margin-bottom:0px; margin-top:5px;">Create Ticket</a>
			</div>
		</div>
		<table id="listTickets" class="table">
			<thead class=".table-light">
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
	<?php include('add_ticket_model.php'); ?>
</div>	
<?php include('../inc/footer.php');?>