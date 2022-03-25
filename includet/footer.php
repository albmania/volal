
   <footer class="footer" id="footer">
      <div class="footer-top">
         <div class="container">
            <div class="row">
               <div class="col-md-4 footer-box">
                  <i class="icon icon-map-marker2"></i>
                  <div class="footer-box-content">
                     <h3><?php echo $gjuha['FOOTER_TXT_ADRESA'];?></h3>
                     <p><?php echo $FOOTER_ADRESA ;?></p>
                  </div>
               </div><!-- Box 1 end-->
               <div class="col-md-4 footer-box">
                  <i class="icon icon-phone3"></i>
                  <div class="footer-box-content">
                     <h3><?php echo $FOOTER_TEL ;?></h3>
                     <p><?php echo $gjuha['FOOTER_TXT_TEL'];?></p>
                  </div>
               </div><!-- Box 2 end-->
               <div class="col-md-4 footer-box">
                  <i class="icon icon-envelope"></i>
                  <div class="footer-box-content">
                     <h3><?php echo fshihEmail($FOOTER_EMAIL);?></h3>
                     <p><?php echo $gjuha['FOOTER_TXT_EMAIL'];?></p>
                  </div>
               </div><!-- Box 3 end-->
            </div><!-- Content row end-->
         </div><!-- Container end-->
      </div><!-- Footer top end-->
      <div class="footer-main">
         <div class="container">
            <div class="row">
               <div class="col-lg-3 col-md-6 footer-widget footer-about">
                  <div class="footer-logo">
                     <a href="index-2.html">
                        <img class="img-fluid" src="<?php echo $MAIN_WEB_URL;?>asetet/images/logo/footer_logo.png" alt="">
                     </a>
                  </div>
                  <p><?php echo $gjuha['FOOTER_TXT_ABOUT'];?></p>
                  <div class="footer-social">
                     <ul class="unstyled">
                        <li><a href="<?php echo $SOC_FCB;?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="<?php echo $SOC_INSTA;?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="<?php echo $SOC_INSTA2;?>" target="_blank"><i class="fab fa-instagram-square"></i></a></li>
                        
                     </ul> <!-- Ul end -->
                  </div><!-- Footer social end-->
               </div> <!-- Col End -->
               <!-- About us end-->
               <div class="col-lg-3 col-md-6 footer-widget widget-service">
                  <h3 class="widget-title"><?php echo $gjuha['FOOTER_SHERBIMET'];?></h3>
                  <ul class="unstyled">
                      <?php

    $sql = "SELECT * from sherbime
			WHERE sherbimeIndex='Po' ORDER BY RAND() LIMIT 6";
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
                     <li><a href="<?php echo $WEB_URL.$URL_SHERBIMET;?>"><?php echo $sherbimeEmertimi;?></a></li>
<?php
        }
    }
                      ?>
                      
                  </ul> <!-- Ul end -->
               </div> <!-- Col End -->
                
               <div class="col-lg-3 col-md-6 footer-widget news-widget">
                  <h3 class="widget-title"><?php echo $gjuha['FOOTER_MAKINAT'];?></h3>
                  <ul class="unstyled">
                      <?php
$sql = "SELECT * from makina_shitje, makina_marka, makina_modeli, makina_tipi 
			WHERE mshitjeAktiv='Po' AND mshitjeShitur='Jo' 
            AND mshitjeMarka=mmarkaID 
            AND mshitjeModeli=mmodeliID
            AND mshitjeTipi=mtipiID
            ORDER BY mshitjeID DESC LIMIT 4";
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
?>
                      
                     <li class="news-text">
                        <a href="<?php echo $WEB_URL.$URL_MAKINA.'shitet-'.gjeneroURL($marka).'-'.gjeneroURL($modeli).'-'.gjeneroURL($tipi).'-'.gjeneroURL($mshitjeVitiProdhimit).'-'.$mshitjeID.'/' ?>"><span><?php echo $marka.' '.$modeli.' '.$tipi;?></span></a>
                        <span><?php echo $mshitjeKambio.' '.$mshitjeVitiProdhimit;?></span>
                     </li>
        <?php
        }
    }
                      ?>
                  </ul> <!-- Ul -->
               </div> <!-- Col End -->
               <div class="col-lg-3 col-md-6 footer-widget">
                  <h3 class="widget-title"><?php echo $gjuha['FOOTER_ORARI_TITULL'];?></h3>
                  <?php echo $gjuha['FOOTER_ORARI_HENE'];?>
               </div> <!-- Col End -->
            </div><!-- Content row end-->
         </div><!-- Container end-->
      </div><!-- Footer Main-->
         <div class="copyright">
            <div class="container">
               <div class="row">
                  <div class="col-lg-6 col-md-12">
                     <div class="copyright-info"><span><?php echo $gjuha['FOOTER_COPYRIGHT'];?></span></div>
                  </div>
                  <div class="col-lg-4 col-md-12">
                     <div class="footer-menu">
                        <ul class="nav unstyled">
                           <li><a href="<?php echo $WEB_URL;?>privatesia-e-te-dhenave-1/">Privatësia e të dhënave</a></li>
                        </ul> <!-- Nav End -->
                     </div> <!-- Footer menu end -->
                  </div> <!-- Col End -->
                   
                   <div id="mundesuarNgaW" class="col-lg-2 col-md-12">
                       <span>Me <i class="fas fa-heart"></i> Nga</span> <a href="https://www.albmania.group/?utm_source=VolAlService&utm_medium=Ikona&utm_campaign=Copyright-Front" target="_blank" alt="Albmania Group" title="Albmania Group"><img class="logoAMG" src="/asetet/images/ikona-amg.png" alt="Albmania Group"></a>
                   </div>
               </div><!-- Row end-->
				
				<div class="row">
					<div class="col-lg-12 col-md-12 txtVogel">
						<?php echo $gjuha['FOOTER_INFO_VOLVO_CPR'];?>
					</div>
				</div>
				
            </div><!-- Container end-->
            <div class="back-to-top" id="back-to-top" data-spy="affix" data-offset-top="10" style="display: block;">
               <button class="back-btn" title="Back to Top">
                  <i class="fa fa-angle-double-up"></i><!-- icon end-->
               </button><!-- button end-->
            </div><!-- Back to top -->
         </div><!-- Copyright end-->
      </footer> <!-- Footer End -->

