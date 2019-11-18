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
  <!-- Site made with Mobirise Website Builder v4.11.2, https://mobirise.com -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v4.11.2, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/logo4.png" type="image/x-icon">
  <meta name="description" content="">

  <title>PLEMA Digital</title>
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons/mobirise-icons.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/tether/tether.min.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="preload" as="style" href="assets/mobirise/css/mbr-additional.css">
  <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">

</head>
<body>
    <section class="cid-rI3jhRoPqe mbr-fullscreen mbr-parallax-background" id="header2-0">
    <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(35, 35, 35);"></div>

    <div class="container align-center">
        <div class="row justify-content-md-center">
            <div class="mbr-white col-md-10">
                <h1 class="mbr-section-title mbr-bold pb-3 mbr-fonts-style display-1">
                    PLEMA Digital</h1>

                <p class="mbr-text pb-3 mbr-fonts-style display-5">
                    Philippine Local Emergency App.</br>Providing emergency services communication to every Juan and Juana.
                </p>
								<p align="center">
									<?php
									if($user->isLoggedIn()){?>
                                        <a class="btn btn-primary" href="users/account.php" role="button"><?=lang("ACCT_HOME");?> &raquo;</a>
                                        <a class="btn btn-primary" href="users/dashboard.php" role="button"><?=lang("BE_DASH");?> &raquo;</a>
									<?php }else{?>
										<a class="btn btn-warning" href="users/login.php" role="button"><?=lang("SIGNIN_TEXT");?> &raquo;</a>
										<a class="btn btn-info" href="users/join.php" role="button"><?=lang("SIGNUP_TEXT");?> &raquo;</a>
									<?php }?>
								</p>
            </div>
        </div>
    </div>
    <div class="mbr-arrow hidden-sm-down" aria-hidden="true">
        <a href="#next">
            <i class="mbri-down mbr-iconfont"></i>
        </a>
    </div>
</section>

<section class="engine"><a href="https://mobirise.info/h">create a web page for free</a></section><section class="team1 cid-rI3jn3wCOh" id="team1-1">



    <div class="container align-center">
        <h2 class="pb-3 mbr-fonts-style mbr-section-title display-2">
            OUR AWESOME TEAM
        </h2>

        <div class="row media-row">

        <div class="team-item col-lg-3 col-md-6">
                <div class="item-image">
                    <img src="assets/images/face3.jpg" alt="" title="">
                </div>
                <div class="item-caption py-3">
                    <div class="item-name px-2">
                        <p class="mbr-fonts-style display-5">
                           Enzo</p>
                    </div>


                </div>
            </div><div class="team-item col-lg-3 col-md-6">
                <div class="item-image">
                    <img src="assets/images/face2.jpg" alt="" title="">
                </div>
                <div class="item-caption py-3">
                    <div class="item-name px-2">
                        <p class="mbr-fonts-style display-5">
                           Karen</p>
                    </div>


                </div>
            </div><div class="team-item col-lg-3 col-md-6">
                <div class="item-image">
                    <img src="assets/images/face3.jpg" alt="" title="">
                </div>
                <div class="item-caption py-3">
                    <div class="item-name px-2">
                        <p class="mbr-fonts-style display-5">
                           Jeff</p>
                    </div>


                </div>
            </div></div>
    </div>
</section>

<section class="team1 cid-rI3jqrY2L3" id="team1-2">



    <div class="container align-center">


        <div class="row media-row">

        <div class="team-item col-lg-3 col-md-6">
                <div class="item-image">
                    <img src="assets/images/face3.jpg" alt="" title="">
                </div>
                <div class="item-caption py-3">
                    <div class="item-name px-2">
                        <p class="mbr-fonts-style display-5">
                           McJoben</p>
                    </div>


                </div>
            </div><div class="team-item col-lg-3 col-md-6">
                <div class="item-image">
                    <img src="assets/images/face2.jpg" alt="" title="">
                </div>
                <div class="item-caption py-3">
                    <div class="item-name px-2">
                        <p class="mbr-fonts-style display-5">
                           Camille</p>
                    </div>


                </div>
            </div></div>
    </div>
</section>


  <script src="assets/web/assets/jquery/jquery.min.js"></script>
  <script src="assets/popper/popper.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/tether/tether.min.js"></script>
  <script src="assets/smoothscroll/smooth-scroll.js"></script>
  <script src="assets/parallax/jarallax.min.js"></script>
  <script src="assets/theme/js/script.js"></script>


</body>
</html>


<!-- Place any per-page javascript here -->


<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
