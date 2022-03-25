<?php
include ('includet/head.php');
?>
</head>

<body>
    <?php include ('includet/lart.php'); ?>
    
   <div class="banner-area bg-overlay" id="banner-area" style="background-image:url(<?php echo $MAIN_WEB_URL;?>asetet/images/shirit-logo-volvo.jpg);">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="banner-heading">
                  <h1 class="banner-title"><?php echo $gjuha['FAQE_SHERBIME_TITULL1'];?></h1>
                  <ol class="breadcrumb">
                     <li><a href="<?php echo $WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                     <li><?php echo $gjuha['FAQE_SHERBIME_TITULL_BC'];?></li>
                  </ol><!-- Breadcumb End -->
               </div><!-- Banner Heading end -->
            </div><!-- Col end-->
         </div><!-- Row end-->
      </div><!-- Container end-->
   </div><!-- Banner area end-->

   <section id="main-container" class="main-container ts-srevice-inner">
		<div class="container">

         <div class="row">
            <div class="col-md-12">
               <h2 class="section-title">
                  <?php echo $gjuha['FAQE_SHERBIME_TITULL2'];?>
               </h2>
            </div><!-- Col end -->
         </div><!-- Row End -->

         <div class="row">
             <?php

    $sql = "SELECT * from sherbime
			WHERE sherbimeIndex='Po' ORDER BY RAND() LIMIT 9";
	//echo $sql;
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
			$sherbimeID=$row['sherbimeID'];
            if ($gjuhaR=='en'){
                $sherbimeEmertimi=$row['sherbimeEmertimi_en'];
                $sherbimeTxt=$row['sherbimeTxt_en'];
            }
            if ($gjuhaR=='sq'){
                $sherbimeEmertimi=$row['sherbimeEmertimi_sq'];
                $sherbimeTxt=$row['sherbimeTxt_sq'];
            }
            else {
                $sherbimeEmertimi=$row['sherbimeEmertimi_sq'];
                $sherbimeTxt=$row['sherbimeTxt_sq']; 
            }
            $sherbimeFoto =$row['sherbimeFoto'];
			$sherbimeIkona=$row['sherbimeIkona'];
?>
             
            <div class="col-lg-4 col-md-12 mPoshte20">
               <div class="ts-service-wrapper">
                  <span class="service-img">
                     <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_SHERBIME.$sherbimeFoto;?>" alt="<?php echo $sherbimeEmertimi;?>">
                  </span> <!-- Service Img end -->
                  <div class="service-content">
                     <div class="service-icon">
                        <?php echo $sherbimeIkona;?>
                     </div> <!-- Service icon end -->
                     <h3><?php echo $sherbimeEmertimi;?></h3>
                     <p><?php echo $sherbimeTxt;?></p>
                  </div> <!-- Service content end -->
               </div> <!-- Service wrapper end -->
            </div> 
          <?php
        }//fundi while
    }//fundi if > 0
                ?>   
             
         </div><!-- Row end -->
         

		</div><!-- Container end -->
   </section><!-- Main container end -->
   

    
    
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