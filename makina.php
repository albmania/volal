<?php
include ('includet/head.php');
?>
    <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/magnific-popup.css">
</head>

<body>
    <?php include ('includet/lart.php'); ?>
    <?php
    /******************************************************************************************/
    // IF mshitjeID
    /******************************************************************************************/
    if (isset($_GET['mshitjeID'])){
                        $mshitjeID=checkNr($_GET['mshitjeID']);
                        $sql = "SELECT * from makina_shitje, makina_marka, makina_modeli, makina_tipi WHERE mshitjeID = $mshitjeID AND mshitjeAktiv='Po' AND mshitjeMarka=mmarkaID AND mshitjeModeli=mmodeliID AND mshitjeTipi=mtipiID";
                        //echo $sql;
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $mshitjeID  = $row["mshitjeID"];
                                $mmarkaMarka = $row["mmarkaMarka"];
                                $mmarkaLogo = $row["mmarkaLogo"];
                                $mmodeliModeli  = $row["mmodeliModeli"];
                                $mtipiTipi  = $row["mtipiTipi"];
                                $mshitjeStruktura  = $row["mshitjeStruktura"];
                                $mshitjeVitiProdhimit  = $row["mshitjeVitiProdhimit"];
                                $mshitjeKapacitetiMotorrit  = $row["mshitjeKapacitetiMotorrit"];
                                $mshitjeKarburant  = $row["mshitjeKarburant"];
                                $mshitjeNgjyra  = $row["mshitjeNgjyra"];
                                $mshitjeNrVendeve  = $row["mshitjeNrVendeve"];
                                $mshitjeKambio  = $row["mshitjeKambio"];
                                $mshitjePrejardhja  = $row["mshitjePrejardhja"];
                                $mshitjeCmimi  = $row["mshitjeCmimi"];
                                $mshitjeTargaAL  = $row["mshitjeTargaAL"];
                                $mshitjeKilometra  = $row["mshitjeKilometra"];
                                $mshitjePershkrimi  = $row["mshitjePershkrimi"];
                                $mshitjeShitur  = $row["mshitjeShitur"];
                                $mshitjeFotografi  = $row["mshitjeFotografi"];
                                list($mshitjeFotografi1) = explode(',',$mshitjeFotografi);
    
    ?>
    <div class="banner-area bg-overlay" id="banner-area" style="background-image:url(<?php echo $MAIN_WEB_URL;?>asetet/images/shirit-logo-volvo.jpg);">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="banner-heading">
                  <h1 class="banner-title"><?php echo $gjuha['INDEX_MAKINA_SHITJE_SHITET'].' '.$mmarkaMarka.' '.$mmodeliModeli.' '.$mtipiTipi.' '.$mshitjeVitiProdhimit;?></h1>
                  <ol class="breadcrumb">
                     <li><a href="<?php echo $WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                      <li><a href="<?php echo $WEB_URL.$URL_MAKINA;?>"><?php echo $gjuha['BC_MAKINA'];?></a></li>
                     <li><?php echo $mmarkaMarka.' '.$mmodeliModeli.' '.$mtipiTipi.' '.$mshitjeVitiProdhimit;?></li>
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
                      
                      <div class="logoMarka">
                        <img src="<?php echo $MAIN_WEB_URL.$UP_MAKINA_LOGO.$mmarkaLogo;?>">
                     </div>

                     <div class="widget no-padding no-border listaOpsioneve">
                        <ul class="service-menu unstyled">
                           <li><img src="<?php echo $MAIN_WEB_URL;?>asetet/images/volvo.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_MODELI'];?>"> <?php echo $mmarkaMarka.' '.$mmodeliModeli.' '.$mtipiTipi;?></li>
                            <li> <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/motorri.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_MOTORRI'];?>"> <?php echo $mshitjeKapacitetiMotorrit;?> <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/karburant.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_KARBURANTI'];?>"> <?php echo $mshitjeKarburant;?></li>
                            <li><img src="<?php echo $MAIN_WEB_URL;?>asetet/images/struktura.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_STRUKTURA'];?>"> <?php echo $mshitjeStruktura;?> <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/vende.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_VENDE'];?>"> <?php echo $mshitjeNrVendeve;?> Vende</li>
                            <li><img src="<?php echo $MAIN_WEB_URL;?>asetet/images/kalendar.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_VITI'];?>"> <?php echo $mshitjeVitiProdhimit;?> <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/kambio.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_KAMBIO'];?>"> <?php echo $mshitjeKambio;?></li>
                            <li><img src="<?php echo $MAIN_WEB_URL;?>asetet/images/km.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_KM'];?>"> <?php echo $mshitjeKilometra;?> Kilometra</li>
                            <li><img src="<?php echo $MAIN_WEB_URL;?>asetet/images/ngjyra.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_NGJYRA'];?>"> <?php echo $mshitjeNgjyra;?> <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/origjina.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_ORIGJINA'];?>"> <?php echo $mshitjePrejardhja;?></li>
                            <li><img src="<?php echo $MAIN_WEB_URL;?>asetet/images/targaal.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_TARGAAL'];?>"> <?php if ($mshitjeTargaAL=='Po'){ echo 'Me Targa Shqiptare';} else { echo 'Pa Targa';}?></li>
                           
                        </ul>
                     </div>

                     

                     <div class="widget">
                        <h3 class="widget-title"><?php echo $gjuha['FAQE_MAKINA_ORARET_SHITJE_TITULL'];?></h3>
                        <?php echo $gjuha['FAQE_MAKINA_ORARET_SHITJE'];?>
                     </div> <!-- Widget End -->

                  </div><!-- Sidebar end -->
               </div><!-- Sidebar Col end -->

                
                
               <div class="col-lg-9 col-md-12">
                   <div class="ts-gallery">
                       <div class="container">

                     <h2 class="section-title">
                        <?php echo '<span>'.$gjuha['INDEX_MAKINA_SHITJE_SHITET'].' </span> '.$mmarkaMarka.' '.$mmodeliModeli.' '.$mtipiTipi.' '.$mshitjeVitiProdhimit;?>
                     </h2>
                           <?php if ($mshitjeShitur=='Po'){ echo '<div class="shiturTek"></div>';}?>
                      <div class="row">
                      <?php
                         $nrRendor = 1;
                          $arraymshitjeFotografi1= explode(',', $mshitjeFotografi);
                          foreach ($arraymshitjeFotografi1 as $mshitjeFotografiTotal1){
                              
                      ?>
                           
                      <div class="col-lg-4 col-md-6">
                          <div class="img-gallery">
                             <a class="gallery-popup" href="<?php echo $MAIN_WEB_URL.$UP_MAKINA_SHITJE.$mshitjeFotografiTotal1;?>" title="<?php echo $mmarkaMarka.' '.$mmodeliModeli.' '.$mtipiTipi;?> :-: <?php echo $nrRendor++;?>">
                                <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_MAKINA_SHITJE.$mshitjeFotografiTotal1;?>" alt="<?php echo $gjuha['INDEX_MAKINA_SHITJE_SHITET'].' '.$mmarkaMarka.' '.$mmodeliModeli.' '.$mtipiTipi.' '.$mshitjeVitiProdhimit;?>">
                                   <div class="gallery-content">
                                        <h3><?php echo $mmarkaMarka.' '.$mmodeliModeli.' '.$mtipiTipi;?></h3>
                                        <p><?php echo $gjuha['MAKINA_OPSIONE_VITI'].': '.$mshitjeVitiProdhimit.' '.$gjuha['MAKINA_OPSIONE_KAMBIO'].': '.$mshitjeKambio;?></p>
                                   </div>
                             </a>
                          </div>
                        </div>
                    <?php
                          }
                                ?>
                           </div>

                     <h3 class="column-title no-border">
                        <?php echo $gjuha['FAQE_MAKINA_PERSHKRIMI'];?>
                     </h3>
                     <p><?php echo $mshitjePershkrimi ;?></p>
                     

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
                  <h1 class="banner-title"><?php echo $gjuha['FAQE_MAKINA_TITULL1'];?></h1>
                  <ol class="breadcrumb">
                     <li><a href="<?php echo $WEB_URL;?>"><?php echo $gjuha['HOME'];?></a></li>
                     <li><?php echo $gjuha['FAQE_MAKINA_TITULL_BC'];?></li>
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
                  <?php echo $gjuha['FAQE_MAKINA_TITULL2'];?>
               </h2>
            </div><!-- Col end -->
         </div><!-- Row End -->

         <div class="row">
             <?php

    $sql = "SELECT * from makina_shitje, makina_marka, makina_modeli, makina_tipi 
			WHERE mshitjeAktiv='Po' AND mshitjeIndex='Po' AND mshitjeShitur='Jo' 
            AND mshitjeMarka=mmarkaID 
            AND mshitjeModeli=mmodeliID
            AND mshitjeTipi=mtipiID
            ORDER BY mshitjeID DESC LIMIT 9";
	//echo $sql;
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
			$mshitjeID=$row['mshitjeID'];
            
            $marka=$row['mmarkaMarka'];
            $logo=$row['mmarkaLogo'];
            $modeli=$row['mmodeliModeli'];
            $tipi=$row['mtipiTipi'];
            
            $mshitjeStruktura=$row['mshitjeStruktura'];
            $mshitjeVitiProdhimit=$row['mshitjeVitiProdhimit'];
            $mshitjeKarburant=$row['mshitjeKarburant'];
            $mshitjeNgjyra=$row['mshitjeNgjyra'];
            $mshitjeNrVendeve=$row['mshitjeNrVendeve'];
            $mshitjeKambio=$row['mshitjeKambio'];
            $mshitjePrejardhja=$row['mshitjePrejardhja'];
            $mshitjeTargaAL=$row['mshitjeTargaAL'];
            $mshitjeKilometra=$row['mshitjeKilometra'];
            $mshitjeFotografi=$row['mshitjeFotografi'];
            list($mshitjeFotografi1) = explode(',',$mshitjeFotografi);
            $mshitjeKapacitetiMotorrit=$row['mshitjeKapacitetiMotorrit'];
            $mshitjeStruktura=$row['mshitjeStruktura'];
            $mshitjeShitur=$row['mshitjeShitur'];
?>
             
            <div class="col-lg-4 col-md-12 mPoshte20">
                  <div class="ts-makina-shitje-wrapper">
                     <span class="makina-shitje-img">
                         <a href="<?php echo $WEB_URL.$URL_MAKINA.'shitet-'.gjeneroURL($marka).'-'.gjeneroURL($modeli).'-'.gjeneroURL($tipi).'-'.gjeneroURL($mshitjeVitiProdhimit).'-'.$mshitjeID.'/' ?>"><img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_MAKINA_SHITJE.$mshitjeFotografi1;?>" alt="<?php echo $gjuha['INDEX_MAKINA_SHITJE_SHITET'].' '.$marka.' '.$modeli.' '.$tipi.' '.$mshitjeKambio.' '.$mshitjeKarburant.' '.$mshitjeVitiProdhimit;?>"></a>
                         <?php if ($mshitjeShitur=='Po'){ echo '<div class="shitur"></div>';}?>
                     </span> <!-- makina-shitje Img end -->
                     <div class="makina-shitje-content">
                        <div class="makina-shitje-icon">
                          <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_MAKINA_LOGO.$logo;?>" alt="<?php echo $marka;?>">
                        </div> <!-- makina-shitje icon end -->
                        <h3><a href="<?php echo $WEB_URL.$URL_MAKINA.'shitet-'.gjeneroURL($marka).'-'.gjeneroURL($modeli).'-'.gjeneroURL($tipi).'-'.gjeneroURL($mshitjeVitiProdhimit).'-'.$mshitjeID.'/' ?>"><?php echo $gjuha['INDEX_MAKINA_SHITJE_SHITET'].' '.$marka.' '.$modeli.' '.$tipi.' '.$mshitjeKambio.' '.$gjuha['INDEX_MAKINA_SHITJE_SHITET_PRAPA'];?></a></h3>
                        <p>
                        <div class="makinaOpsione">
                            <div class="opsionListe full">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/volvo.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_MODELI'];?>"> <?php echo $marka.' '.$modeli.' '.$tipi;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/motorri.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_MOTORRI'];?>"> <?php echo $mshitjeKapacitetiMotorrit;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/struktura.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_STRUKTURA'];?>"> <?php echo $mshitjeStruktura;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/kalendar.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_VITI'];?>"> <?php echo $mshitjeVitiProdhimit;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/karburant.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_KARBURANTI'];?>"> <?php echo $mshitjeKarburant;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/kambio.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_KAMBIO'];?>"> <?php echo $mshitjeKambio;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/vende.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_VENDE'];?>"> <?php echo $mshitjeNrVendeve;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/targaal.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_TARGAAL'];?>"> <?php echo $mshitjeTargaAL;?>
                            </div>
                            
                            <div class="opsionListe gjysem">
                                <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/km.png" alt="<?php echo $gjuha['MAKINA_OPSIONE_KM'];?>"> <?php echo $mshitjeKilometra;?>
                            </div>
                            
                         </div>
                        </p>
                     </div> <!-- makina-shitje content end -->
                  </div> <!-- makina-shitje wrapper end -->
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