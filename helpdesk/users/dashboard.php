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

// require '../init.php';
include '../users/TicketManager.php';
// print_r($_SESSION);
$tickets = $tickets->getAllTickets();

?>

<title>PLeMA - Philippine Local eMergency App</title>
<div class="container dash-container rounded" style="margin-top:2%;">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<p>This is where you view and manage your tickets.</p>	

			<table id="listTickets" class="table table-hover table-responsive table-bordered" width="100%" style="margin:0; padding:0; display: table;">
				<thead class="thead-light">
					<tr>
						<th scope="col">Subscriber Name</th>
						<th scope="col">Thread Status</th>
						<th scope="col">Actions</th>
					</tr>
				</thead>
				<tbody class="table-striped">
					<?php foreach ($tickets as $index => $ticket){
							echo '<tr><td>'.$ticket[0].'</td>'.
									 '<td>'.$ticket[1].'</td>'.
									 '<td>'.$ticket[2].'</td>'.'</tr>';
							}
					?>
				</tbody>
			</table>
		</div>
	</div>   		
</div>

<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready(function() { 

		// $.ajax({
		// 		url: '../users/TicketManager.php',
		// 		type:'POST',
		// 		data: {
		// 				action:'showAllTickets'
		// 		},
		// 		error: function() {
						
		// 		},
		// 		dataType: 'json',
		// 		success: function(data) {
		// 			$('#listTickets').dataTable({
		// 				"lengthChange": false,
		// 				"processing":true,
		// 				"dataSrc": "",
		// 				"order":[],
		// 				"data": data['data'],
		// 				"columnDefs":[
		// 					{
		// 						"targets":[0, 1, 2, 3, 4, 5, 6, 7],
		// 						"orderable":false,
		// 					},
		// 				],
		// 				"pageLength": 10
		// 			});
		// 			console.log('yehey');
		// 		}
		// });
	});
</script>

<style>

.action-btn{
	padding-top:3px;
	padding-bottom:2px;
	padding-left:5px;
	padding-right:5px;
}

.row-content td{
	padding-bottom:9px;
}

</style>