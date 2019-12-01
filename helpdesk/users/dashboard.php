<?php
// This is a user-facing page
/*
PLEMA Digital 5
An Open Source PHP User Management System
by the PLEMA Digital Team at http://PLEMA.digital

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
ini_set("allow_url_fopen", 1);
if(isset($_SESSION)){session_destroy();}
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
$hooks =  getMyHooks();
includeHook($hooks,'pre');
if($settings->twofa == 1){
  $google2fa = new PragmaRX\Google2FA\Google2FA();
}
?>

<title>PLeMA - Philippine Local eMergency App</title>
<div class="container dash-container rounded" style="margin-top:2%;">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<p>This is where you view and manage your tickets.</p>	

			<a href="#" id="open-ticket-details">Modal</a>
	
			<table id="listTickets" class="table table-hover table-responsive" width="100%" style="margin:0; padding:0;">
				<thead class="thead-light">
					<tr>
						<th scope="col">S/N</th>
						<th scope="col">Ticket ID</th>
						<th scope="col">Subject</th>
						<th scope="col">Created By</th>					
						<th scope="col">Created</th>	
						<th scope="col">Status</th>
						<th scope="col">Assignee</th>
						<th scope="col">Actions</th>
					</tr>
				</thead>
				<tbody class="table-striped">
				</tbody>
			</table>
		</div>
	</div>   		
</div>

<!-- <script src="js/jquery.min.js" type="text/javascript"></script> -->
<!-- <script src="js/jquery.dataTables.min.js" type="text/javascript"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() { 
	console.log('heeeeyyy');

	$('#listTickets').DataTable({
		"ajax":{
			url:"../users/classes/TicketManager/manageTickets",
			type:"POST",
			data:{
				action:'listTicket'
			},
			dataType:"json",
			success:function (response){
					alert(reponse);
				}
			
		},
		"columnDefs":[
			{
				"targets":[0, 6, 7, 8, 9],
				"orderable":false,
			},
		],
		"pageLength": 10,
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
	});
	
	$('#listTickets').DataTable().ajax.reload();

});

$('#open-ticket-details').click(function(){
	console.log('here');
	$.ajax({
     type: "POST",
	url: '../users/classes/TicketManager/manageTickets', 
	data: {
		action:'getTicketDetails',
		id:34
	},
	dataType: "json",
	success: function (data) {
		alert(data);
	}
	})
})

</script>