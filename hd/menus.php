<nav class="navbar navbar-inverse" style="background:#034f84; font-color:#ffffff;font-weight:bold;">
	<div class="container-fluid">
		<div class="navbar-header">
			<a href="index.php" class="navbar-brand" id="index_menu">Dashboard</a>
		</div>
		<!-- <ul class="nav navbar-nav menus">		
			<li><a href="#">Create Ticket</a></li>			
		</ul> -->
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></a>
				<!-- <img src="//gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=100" width="20px">&nbsp;<?php //if(isset($_SESSION["userid"])) { echo $user['nick_name']; } ?></a> -->
				<ul class="dropdown-menu">
					<li><a href="settings.php"><span class="glyphicon glyphicon-user" aria-hidden="true">&nbsp;Account</span></a></li>
					<li><a href="logout.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true">&nbsp;Logout</span></a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>