<nav class="navbar navbar-inverse" style="background:#00796B;color:#f6f8f9;font-weight:bold;">
	<div class="container-fluid">
		<div class="navbar-header">
			<a href="index.php" class="navbar-brand" id="index_menu">Dashboard</a>
		</div>
		<ul class="nav navbar-nav menus">		
			<li><a href="#" id="createTicket">Create Ticket</a></li>			
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> 
				<img src="//gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=100" width="20px">&nbsp;<?php if(isset($_SESSION["userid"])) { echo $user['nick_name']; } ?></a>
				<ul class="dropdown-menu">
					<li><a href="settings.php">Account</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>