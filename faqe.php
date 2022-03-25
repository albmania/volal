<?php
include ('includet/head.php');
?>
</head>

<body>
    <?php include ('includet/lart.php'); ?>
	<?php
	
	 if (isset($_GET['faqeid'])){
				$faqeID=checkNr($_GET['faqeid']);
				$sql = "SELECT * from faqe WHERE faqeID = $faqeID ";
				//echo $sql;
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result)) {
							$faqeFoto=$row['faqeFoto'];
							list($faqeFoto1) = explode(',',$faqeFoto);
							
							if ($gjuhaR=="en"){$faqeEmri=$row["faqeEmri_en"];$faqeTxt=$row["faqeTxt_en"];}
                            elseif ($gjuhaR=="sq"){$faqeEmri=$row["faqeEmri_sq"];$faqeTxt=$row["faqeTxt_sq"];}
							else {$faqeEmri=$row["faqeEmri_sq"];$faqeTxt=$row["faqeTxt_sq"];}
							
								?>
    
   <div class="banner-area bg-overlay" id="banner-area" style="background-image:url(<?php echo $MAIN_WEB_URL;?>asetet/images/shirit-volal-servisi.jpg);">
      <div class="container">
            <div class="row justify-content-center">
               <div class="col">
                  <div class="banner-heading">
                        <h1 class="banner-title"><?php echo $faqeEmri;?></h1>
                        <ol class="breadcrumb">
                            <li><a href="<?php echo $WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                            <li><?php echo $faqeEmri;?></li>
                        </ol>
                  </div> <!-- Banner heading -->
               </div><!-- Col end-->
            </div><!-- Row end-->
      </div><!-- Container end-->
   </div><!-- Banner area end-->

   <section id="main-container" class="main-container pb-0">
      <div class="ts-about-us">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <h2 class="section-title">
                     <?php echo $faqeEmri;?>
                  </h2>
               </div><!-- Col end -->
            </div><!-- Row End -->
            <div class="row overflow-hidden no-gutters">
				<?php echo $faqeTxt;?>
            </div> <!-- Row End -->
         </div> <!-- Container Fluid -->
      </div><!-- Ts About Us end -->
   </section> <!-- About End -->
	<?php
						}
					}
	 }
	?>

    <?php include ('includet/blloqe/shiritPartneret.php'); ?>
    
    <?php include ('includet/footer.php'); ?>
    
    <!-- initialize jQuery Library -->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/popper.min.js"></script>
    <!-- Bootstrap jQuery -->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/bootstrap.min.js"></script>
    <!-- Owl Carousel -->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/owl-carousel.2.3.0.min.js"></script>
    <!-- START js copy section -->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/bootstrap-datepicker-lang/en.js"></script>
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/jquery.timepicker.min.js"></script>
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/select2.full.min.js"></script>
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/select2-lang/en.js"></script>
    <!--[if lt IE 9]><script src="contactme/js/EQCSS-polyfills-1.7.0.min.js"></script><![endif]-->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/EQCSS-1.7.0.min.js"></script>
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/contactme-config.js"></script>
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/contactme/contactme-1.6.js"></script>
    <!-- To enable Google reCAPTCHA, uncomment the next line: -->
    <!-- <script src="https://www.google.com/recaptcha/api.js?onload=initRecaptchas&render=explicit" async defer></script> -->
    <!-- END js copy section -->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/main.js"></script>
    
    <?php include ('includet/end.php'); ?>