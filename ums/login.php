<?php 
include('class/User.php');
$user = new User();
$errorMessage =  $user->login();
include('include/header.php');
?>

<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<title>PLeMA - Philippine Local eMergency App User Management</title>

<div class="container contact" style="margin-top: 10%">	
	<div class="row justify-content-center">
		<div class="col-md-6">                    
			<div class="panel panel-info" >
				<div class="panel-heading" style="background:#00796B;color:white;">
					<div class="panel-title display-4">User Login</div>                        
				</div> 
				<div style="padding-top:30px" class="panel-body" >
					<?php if ($errorMessage != '') { ?>
						<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $errorMessage; ?></div>                            
					<?php } ?>
					<form id="loginform" class="form-horizontal" role="form" method="POST" action="">                                    
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon" style = "padding-right:25px;"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" class="form-control" id="loginId" name="loginId"  value="<?php if(isset($_COOKIE["loginId"])) { echo $_COOKIE["loginId"]; } ?>" placeholder="email">                                        
						</div>                                
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon" style = "padding-right:25px;"><i class="glyphicon glyphicon-lock"></i></span>
							<input type="password" class="form-control" id="loginPass" name="loginPass" value="<?php if(isset($_COOKIE["loginPass"])) { echo $_COOKIE["loginPass"]; } ?>" placeholder="password">
						</div>            
						<div class="row">
							<div class="input-group col-md-9" style="margin-left:25px;">
								<div class="checkbox">
								<label>
									<input  type="checkbox" id="remember" name="remember" <?php if(isset($_COOKIE["loginId"])) { ?> checked <?php } ?>> Remember me
								</label>
								<label><a href="forget_password.php">Forget your password</a></label>	
								</div>
							</div>
							<div class="form-group col-md-3" style="padding-left:35px;">                               
								<div class="controls">
									<input type="submit" name="login" value="Login" class="btn btn-success btn-primary btn-lg" style="padding-right:20px; padding-left:20px;">						  
								</div>						
							</div>
						</div> 
						<div class="form-group">
							<div class="col-md-12 control">
								<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
									Don't have an account! 
								<a href="register.php">
									Register 
								</a>Here. 
								</div>
							</div>
						</div>    	
					</form>   
					<div class="row justify-content-center">	
								<div style="margin-top:10px;margin-bottom: 0px;" class="form-group">
									<div class="col-sm-12 controls">
										Admin: admin@webdamn.com // password:123	<br>
										User: smith@webdamn.com // 	password:123
									</div>
								</div>
							</div>
				</div>                     
			</div>  
		</div>	
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<?php include('include/footer.php');?>