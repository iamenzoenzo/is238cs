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
<?php //include('inc/container.php');?>
<div class="row" style="margin-top:5%; text-align:center; color:#ffffff; text-shadow: 2px 2px 3px #000000;">
	<h1>PLeMA - Philippine Local eMergency App</h1>
</div>
<div class="container dash-container rounded" >	
	<div class="row home-sections">
		<?php //include('menus.php'); ?>		
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
					<th>Actions</th>	
					<th></th>		
					<th></th>		
				</tr>
			</thead>
		</table>
	</div>
</div>	