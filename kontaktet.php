<?php
include ('includet/head.php');
?>
</head>

<body>
    <?php include ('includet/lart.php'); ?>
    
       <div class="banner-area bg-overlay" id="banner-area" style="background-image:url(<?php echo $MAIN_WEB_URL;?>asetet/images/shirit-volal-servisi.jpg);">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="banner-heading">
                  <h1 class="banner-title"><?php echo $gjuha['FAQE_KONTAKT_TITULL'];?></h1>
                  <ol class="breadcrumb">
                     <li><a href="<?php echo $MAIN_WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                     <li><?php echo $gjuha['FAQE_KONTAKT_TITULL_BC'];?></li>
                  </ol>
                  <!-- Breadcumb End -->
               </div>
               <!-- Banner Heading end -->
            </div>
            <!-- Col end-->
         </div>
         <!-- Row end-->
      </div>
      <!-- Container end-->
   </div>
   <!-- Banner area end-->
<div class="mapouter">
                  <div class="gmap_canvas">
                     
                      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5035.306566842884!2d19.679586164233957!3d41.37384143492515!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x13502ea4fd4033af%3A0x1bea32b4c9e94f5!2sVol%20Al%20Service!5e0!3m2!1sen!2s!4v1615217518693!5m2!1sen!2s" width="450" height="450" style="border:0;" allowfullscreen="" loading="lazy"  id="gmap_canvas"></iframe>
                  </div>
               </div>
   <section id="main-container" class="main-container ts-contact-us">
      <div class="container">
          <?php include('includet/blloqe/indexPoshteSlider.php');?>

      </div>
      <!-- Container end -->
   </section>
   <!-- Main container end -->

    
    
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