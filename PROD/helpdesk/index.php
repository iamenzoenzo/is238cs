<?php
if(file_exists("install/index.php")){
	//perform redirect if installer files exist
	//this if{} block may be deleted once installed
	header("Location: install/index.php");
}
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if(isset($user) && $user->isLoggedIn()){
}
?>

<!DOCTYPE html>
<html  >
<head>
  <!-- Site made with Mobirise Website Builder v4.11.5, https://mobirise.com -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v4.11.5, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/logo4.png" type="image/x-icon">
  <meta name="description" content="">

  <title>Home</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/tether/tether.min.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="preload" as="style" href="assets/mobirise/css/mbr-additional.css"><link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">



</head>
<body>
  <section class="header1 cid-rIKluawaYo" id="header16-3">





    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-10 align-center">
                <h1 class="mbr-section-title mbr-bold pb-3 mbr-fonts-style display-1">
                    PLEMA Digital</h1>

                <p class="mbr-text pb-3 mbr-fonts-style display-5"><strong>Philippine Local Emergency App</strong><br>Providing emergency services communication to every Juan and Juana.</p>
                <div class="mbr-section-btn">
                  <?php
									if($user->isLoggedIn()){?>
                                        <a class="btn btn-md btn-secondary display-4" href="users/account.php" role="button"><?=lang("ACCT_HOME");?> &raquo;</a>
                                        <a class="btn btn-md btn-secondary display-4" href="users/dashboard.php" role="button"><?=lang("BE_DASH");?> &raquo;</a>
									<?php }else{?>
										<a class="btn btn-md btn-secondary display-4" href="users/login.php" role="button"><?=lang("SIGNIN_TEXT");?> &raquo;</a>
									<?php }?>
                </div>
            </div>
        </div>
    </div>

</section>


  <section class="engine"><a href="https://mobirise.info/e">make a website for free</a></section><script src="assets/web/assets/jquery/jquery.min.js"></script>
  <script src="assets/popper/popper.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/tether/tether.min.js"></script>
  <script src="assets/smoothscroll/smooth-scroll.js"></script>
  <script src="assets/theme/js/script.js"></script>


</body>
</html>
