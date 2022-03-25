<?php
include ('includet/head.php');
?>
</head>

<body>
    <?php include ('includet/lart.php'); ?>
    
   <div class="banner-area bg-overlay" id="banner-area" style="background-image:url(<?php echo $MAIN_WEB_URL;?>asetet/images/shirit-volal-servisi.jpg);">
      <div class="container">
            <div class="row justify-content-center">
               <div class="col">
                  <div class="banner-heading">
                        <h1 class="banner-title"><?php echo $gjuha['FAQE_RRETHNESH_TITULL1'];?></h1>
                        <ol class="breadcrumb">
                            <li><a href="<?php echo $WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                            <li><?php echo $gjuha['FAQE_RRETHNESH_TITULL_BC'];?></li>
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
                     <?php echo $gjuha['FAQE_RRETHNESH_TITULL2'];?>
                  </h2>
               </div><!-- Col end -->
            </div><!-- Row End -->
            <div class="row overflow-hidden no-gutters">
               <div class="col-lg-7 col-md-12">
                  <div class="box-skew-hidden-left">
                        <div class="box-skew-left">
                           <img class="img-fluid" src="<?php echo $MAIN_WEB_URL;?>asetet/images/rrethnesh-info.jpg" alt="">
                        </div><!-- Box skew left -->
                  </div>
               </div> <!-- Col End -->
               <div class="col-lg-5 col-md-12">
                  <div class="box-skew-right">
                        <div class="box-content-wrapper">
                           <i class="icon-repair"></i>
                           <h2 class="column-title no-border">
                              <?php echo $gjuha['FAQE_RRETHNESH_INFO_TITULL'];?>
                           </h2>
                           <p><?php echo $gjuha['FAQE_RRETHNESH_INFO_TXT'];?></p>
                        </div> <!-- Content wrapper end -->
                  </div> <!-- Content Right End -->
               </div> <!-- Col end -->
            </div> <!-- Row End -->
         </div> <!-- Container Fluid -->
      </div><!-- Ts About Us end -->
   </section> <!-- About End -->

   <section id="ts-history-tab" class="ts-history-tab">
      <div class="container">
         <div class="row">
            <div class="col-lg-7 col-md-12">
               <div class="row">
                  <div class="col-lg-4 col-md-4">
                     <ul class="nav nav-tabs ts-tab" id="myTab" role="tablist">
                        <li class="nav-item">
                           <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                           <i class="icon-history"></i><?php echo $gjuha['FAQE_RRETHNESH_OURHISTORY_TITULL'];?></a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                           <i class="icon-history"></i><?php echo $gjuha['FAQE_RRETHNESH_OURMISSION_TITULL'];?></a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                           <i class="icon-history"></i><?php echo $gjuha['FAQE_RRETHNESH_OURVISION_TITULL'];?></a>
                        </li>
                     </ul><!-- ul end -->
                  </div><!-- Col end -->
                  <div class="col-lg-8 col-md-8">
                     <div class="tab-content ts-tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                           <?php echo $gjuha['FAQE_RRETHNESH_OURHISTORY_TXT'];?>
                        </div> <!-- tab pane end -->
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                           <?php echo $gjuha['FAQE_RRETHNESH_OURMISSION_TXT'];?>
                        </div> <!-- tab pane end -->
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                           <?php echo $gjuha['FAQE_RRETHNESH_OURVISION_TXT'];?>
                        </div><!-- Tab pane end -->
                     </div> <!-- tab content -->
                  </div> <!-- col end -->
               </div><!-- Row end -->
            </div> <!-- Col end -->
            <div class="col-lg-5 col-md-12 text-right">
               <span><img class="img-fluid" src="<?php echo $MAIN_WEB_URL;?>asetet/images/rrethnesh-logo-maskarino.png" alt=""></span>
            </div> <!-- COl end -->
         </div> <!-- Row End -->
      </div> <!-- Container end -->
   </section> <!-- History tab end -->
    <?php
    /*

   <section id="ts-fun-facts" class="ts-fun-facts">
      <div class="container-fluid no-padding">
         <div class="row no-gutters">
            <div class="col-lg-6 col-md-12 align-self-center">
               <div class="box-skew-area-left bg-blue">
                  <div class="box-skew-sm-left">
                     <h2 class="column-title-sm text-white">
                        <span>Automobil </span> was founded in 1999 at USA
                     </h2>
                     <p class="fun-fact-info">A wonderful serenity taken possession into entire soul also like these are main partcreated for the bliss often soul like</p>
                     <div class="row ts-main-fact">
                        <div class="col-md-4">
                           <div class="ts-facts text-center">
                              <i class="icon-customer"></i>
                              <div class="ts-facts-num">
                                 <h3 class="funfact"><span class="counterUp">530</span></h3>
                              </div>
                              <p>Happy Client</p>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="ts-facts text-center">
                              <i class="icon-customer"></i>
                              <div class="ts-facts-num">
                                 <h3 class="funfact"><span class="counterUp">100%</span></h3>
                              </div>
                              <p>Customer Satisfaction</p>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="ts-facts text-center">
                              <i class="icon-customer"></i>
                              <div class="ts-facts-num">
                                 <h3 class="funfact"><span class="counterUp">530</span></h3>
                              </div>
                              <p>Happy Customers</p>
                           </div>
                        </div>
                     </div><!-- row end -->
                  </div> <!-- Newsletter Content end -->
               </div> <!-- Newsletter Left end -->
            </div> <!-- Col End -->
            <div class="col-lg-6 col-md-12">
               <div class="box-skew-area-right">
                  <div class="box-skew-sm-right">
                     <img class="img-fluid" src="<?php echo $MAIN_WEB_URL;?>asetet/images/about/about-img3.jpg" alt="">
                     <div class="testimonial-slide owl-carousel">
                        <div class="testimonial-item">
                           <span class="icon icon-quote22"></span>
                              <p class="quote-text">Sollicitudin, lorem quis biben dum auctor nisi consequat aliquet. Aenean sollicitudin.Proin gravida nibh vel velit auctor ali the are consequat ipsum, nec sagittis</p>
                           <div class="quote-item-footer">
                              <img class="img-fluid" src="<?php echo $MAIN_WEB_URL;?>asetet/images/testimonial/quote_profile.png" alt="">
                              <div class="quote-item-info">
                                 <h3 class="quote-author">Donald Gonzales</h3>
                                 <span class="quote-subtext"> CEO Oracle </span>
                              </div>
                           </div> <!-- Item End -->
                        </div> <!-- Testimonial Single End -->
                        <div class="testimonial-item">
                           <span class="icon icon-quote22"></span>
                              <p class="quote-text"><i>Sollicitudin, lorem quis biben dum auctor nisi consequat aliquet. Aenean sollicitudin.Proin gravida nibh vel velit auctor ali the are consequat ipsum, nec sagittis</i></p>
                           <div class="quote-item-footer">
                              <img class="img-fluid" src="<?php echo $MAIN_WEB_URL;?>asetet/images/testimonial/quote_profile.png" alt="">
                              <div class="quote-item-info">
                                 <h3 class="quote-author">Donald Gonzales</h3>
                                 <span class="quote-subtext"> CEO Oracle </span>
                              </div>
                           </div> <!-- Item End -->
                        </div> <!-- Testimonial Single End -->
                     </div><!-- Testimonial Slide -->
                  </div> <!-- Newsletter content end -->
               </div> <!-- Newsletter right end -->
            </div> <!-- Col end -->
         </div> <!-- Row End -->
      </div> <!-- Container fluid end -->
   </section><!-- Fun Fact -->
    */
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