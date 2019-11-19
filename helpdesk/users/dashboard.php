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