<?php
include ('includet/head.php');
?>
    <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/magnific-popup.css">
</head>

<body>
    <?php include ('includet/lart.php'); ?>
    <?php
    /******************************************************************************************/
    // IF blogID
    /******************************************************************************************/
    if (isset($_GET['blogID'])){
                        $blogID=checkNr($_GET['blogID']);
                        $sql = "SELECT  * FROM blog, blog_kategori WHERE blogKategoria=blogKatID AND blogGjuha='$gjuhaR' AND blogID=$blogID";
                        //echo $sql;
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                    $blogKatID  = $row["blogKatID"];
                    $blogFoto= $row["blogFoto"];
                    $blogKatEmertimi =$row["blogKatEmertimi"];
                    $blogTitulli=$row["blogTitulli"];
                    $blogTxt=$row["blogTxt"];
                    $blogDtPublik=$row["blogDtPublik"];                
                    list($blogFoto1) = explode(',',$blogFoto);
                    
                    $dataOK = date("d-m-Y", strtotime($blogDtPublik));
							$dataDita = date("d", strtotime($blogDtPublik));
                    if ($gjuhaR=='sq'){
							setlocale(LC_TIME, "sq_AL.UTF-8"); //vendos textet ne Shqip per oren
							$dataMuaji = date("F", strtotime($blogDtPublik));
							$dataMuajiAL = strftime('%b', strtotime($blogDtPublik)); // shfaq muajin me fjale ne Shqip
                    }
                            elseif ($gjuhaR=='en'){
							setlocale(LC_TIME, "en_US.UTF-8"); //vendos textet ne anglisht per oren
							$dataMuaji = date("F", strtotime($blogDtPublik));
							$dataMuajiAL = strftime('%b', strtotime($blogDtPublik)); // shfaq muajin me fjale ne anglisht
                    }
                    else {
                        setlocale(LC_TIME, "sq_AL.UTF-8"); //vendos textet ne Shqip per oren
							$dataMuaji = date("F", strtotime($blogDtPublik));
							$dataMuajiAL = strftime('%b', strtotime($blogDtPublik)); // shfaq muajin me fjale ne Shqip
                    }
    
    ?>
    <div class="banner-area bg-overlay" id="banner-area" style="background-image:url(<?php echo $MAIN_WEB_URL;?>asetet/images/shirit-logo-volvo.jpg);">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="banner-heading">
                  <h1 class="banner-title"><?php echo $blogTitulli;?></h1>
                  <ol class="breadcrumb">
                     <li><a href="<?php echo $WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                      <li><a href="<?php echo $WEB_URL;?>"><?php echo $blogKatEmertimi;?></a></li>
                     <li><?php echo $blogTitulli;?></li>
                  </ol><!-- Breadcumb End -->
               </div><!-- Banner Heading end -->
            </div><!-- Col end-->
         </div><!-- Row end-->
      </div><!-- Container end-->
   </div><!-- Banner area end-->
    
    
    <section id="main-container" class="main-container pb-120 makinaTek">
      <div id="ts-service-details" class="ts-service-detials">
         <div class="container">
            <div class="row">

            
              <div class="col-lg-3 col-md-12">

                  <div class="sidebar sidebar-left">
                      

                     <div class="widget no-padding no-border listaOpsioneve">
                        <ul class="service-menu unstyled">
                           <li><?php echo 'Kategorite';?></li>
                        </ul>
                     </div>

                     

                     <div class="widget">
                        <h3 class="widget-title"><?php echo ' ';?></h3>
                        <?php echo ' ';?>
                     </div> <!-- Widget End -->

                  </div><!-- Sidebar end -->
               </div><!-- Sidebar Col end -->

                
                
               <div class="col-lg-9 col-md-12">
                   <div class="ts-gallery">
                       <div class="container">

                     <h2 class="section-title">
                        <?php echo $blogTitulli;?>
                     </h2>
                      <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_BLOG.$blogFoto1;?>" alt="<?php echo $blogTitulli;?>">

                     <p><?php echo $blogTxt;?></p>
                     
						   <div class="row">
                      <?php
                         $nrRendor = 1;
                          $arrayblogFoto1= explode(',', $blogFoto);
								if (sizeof($arrayblogFoto1) > 1){
                          foreach ($arrayblogFoto1 as $blogFotoTotal1){
							  
                              
                      ?>
                           
                      <div class="col-lg-4 col-md-6">
                          <div class="img-gallery">
                             <a class="gallery-popup" href="<?php echo $MAIN_WEB_URL.$UP_BLOG.$blogFotoTotal1;?>" title="<?php echo $blogTitulli;?> :-: <?php echo $nrRendor++;?>">
                                <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_BLOG.$blogFotoTotal1;?>" alt="<?php echo $blogTitulli;?>">
                                   <div class="gallery-content">
                                        <h3><?php echo $blogTitulli;?></h3>
                                        <p><?php echo $blogKatEmertimi;?></p>
                                   </div>
                             </a>
                          </div>
                        </div>
                    <?php
                          }
						  }
                                ?>
                           </div>

                </div>
                   </div>
               </div> <!-- Col end -->
            </div><!-- Main row end -->

         </div><!-- Container end -->
      </div><!-- Service details end -->
      </section>
    <?php
                            }
                        }
                        }//fundi if get mshitjeID
    /******************************************************************************************/
    // fundi IF mshitjeID
    /******************************************************************************************/
    
    /******************************************************************************************/
    // else
    /******************************************************************************************/
    else {
    
    ?>
   <div class="banner-area bg-overlay" id="banner-area" style="background-image:url(<?php echo $MAIN_WEB_URL;?>asetet/images/shirit-logo-volvo.jpg);">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="banner-heading">
                  <h1 class="banner-title"><?php echo $gjuha['FAQE_BLOG_TITULL1'];?></h1>
                  <ol class="breadcrumb">
                     <li><a href="<?php echo $WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                     <li><?php echo $gjuha['FAQE_BLOG_TITULL_BC'];?></li>
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
                  <?php echo $gjuha['FAQE_BLOG_TITULL2'];?>
               </h2>
            </div><!-- Col end -->
         </div><!-- Row End -->

         <div class="row">
             <?php

    $sql = "SELECT  * FROM blog, blog_kategori WHERE blogKategoria=blogKatID AND blogGjuha='$gjuhaR' ORDER BY blogDtPublik DESC LIMIT 3";
	//echo $sql;
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
			$blogID  = $row["blogID"];
                    $blogKatID  = $row["blogKatID"];
                    $blogFoto= $row["blogFoto"];
                    $blogKatEmertimi =$row["blogKatEmertimi"];
                    $blogTitulli=$row["blogTitulli"];
                    $blogTxt=$row["blogTxt"];
                    $blogDtPublik=$row["blogDtPublik"];                
                    list($blogFoto1) = explode(',',$blogFoto);
                    
                    $dataOK = date("d-m-Y", strtotime($blogDtPublik));
							$dataDita = date("d", strtotime($blogDtPublik));
                    if ($gjuhaR=='sq'){
							setlocale(LC_TIME, "sq_AL.UTF-8"); //vendos textet ne Shqip per oren
							$dataMuaji = date("F", strtotime($blogDtPublik));
							$dataMuajiAL = strftime('%b', strtotime($blogDtPublik)); // shfaq muajin me fjale ne Shqip
                    }
                            elseif ($gjuhaR=='en'){
							setlocale(LC_TIME, "en_US.UTF-8"); //vendos textet ne anglisht per oren
							$dataMuaji = date("F", strtotime($blogDtPublik));
							$dataMuajiAL = strftime('%b', strtotime($blogDtPublik)); // shfaq muajin me fjale ne anglisht
                    }
                    else {
                        setlocale(LC_TIME, "sq_AL.UTF-8"); //vendos textet ne Shqip per oren
							$dataMuaji = date("F", strtotime($blogDtPublik));
							$dataMuajiAL = strftime('%b', strtotime($blogDtPublik)); // shfaq muajin me fjale ne Shqip
                    }
?>
             
            <div class="col-lg-4 col-md-12 mPoshte20">
                  <div class="ts-latest-post">
                     <div class="post-media post-image">
                        <img src="<?php echo $MAIN_WEB_URL.$UP_BLOG.$blogFoto1;?>" alt="<?php echo $blogTitulli;?>" class="img-fluid">
                     </div> <!-- Post Media End -->
                     <div class="post-body">
                        <div class="post-date">
                              <span class="day"><?php echo $dataDita;?></span>
                           <span class="month"><?php echo $dataMuajiAL;?></span>
                        </div> <!-- Post Date End -->
                        <div class="post-info">
                           <div class="post-meta">
                              <span class="post-author"><a href="<?php echo $WEB_URL.$URL_BLOG.gjeneroURL($blogKatEmertimi).'-'.$blogKatID.'/' ?>"><?php echo $blogKatEmertimi;?></a></span>
                           </div>
                        </div> <!-- Post Info End -->
                        <h3 class="post-title">
                           <a href="<?php echo $WEB_URL.$URL_BLOG.gjeneroURL($blogKatEmertimi).'-'.$blogKatID.'/'.gjeneroURL($blogTitulli).'-'.$blogID.'/' ?>"><?php echo $blogTitulli;?></a>
                        </h3>
                        <div class="post-text">
                           <p><?php echo limitoShkronja($blogTxt,180);?> </p>
                        </div>
                        <a href="<?php echo $WEB_URL.$URL_BLOG.gjeneroURL($blogKatEmertimi).'-'.$blogKatID.'/'.gjeneroURL($blogTitulli).'-'.$blogID.'/' ?>" class="readmore"><?php echo $gjuha['BLOG_READ_MORE'];?> <i class="fa fa-angle-double-right"></i></a>
                     </div> <!-- Post Body End -->
                  </div> <!-- Latest Post End -->
               </div> 
          <?php
        }//fundi while
    }//fundi if > 0
                ?>   
             
         </div><!-- Row end -->
         

		</div><!-- Container end -->
   </section><!-- Main container end -->
   
<?php
    }//fundi else
    /******************************************************************************************/
    // fundi else
    /******************************************************************************************/    
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
    <!--[if lt IE 9]><script src="contactme/js/EQCSS-polyfills-1.7.0.min.js"></script><![endif]-->
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo $MAIN_WEB_URL;?>asetet/js/main.js"></script>
    
    <?php include ('includet/end.php'); ?>