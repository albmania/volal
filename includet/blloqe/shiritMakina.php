
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
?>
   <section id="ts-makina-shitje-bg" class="ts-makina-shitje-bg bg-overlay">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <h2 class="section-title text-white">
                  <?php echo $gjuha['INDEX_MAKINA_SHITJE'];?>
               </h2>
            </div> <!-- Col End -->
         </div> <!-- Row End -->
      </div><!-- Container end -->
   </section> <!-- makina-shitje BG end -->

   <section id="ts-makina-shitje" class="ts-makina-shitje pb-0">
      <div class="container">
         <div class="row">
            <div class="makina-shitje-carousel owl-carousel">
<?php
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
               <div class="col-md-12">
                  <div class="ts-makina-shitje-wrapper">
                     <span class="makina-shitje-img">
                        <a href="<?php echo $WEB_URL.$URL_MAKINA.'shitet-'.gjeneroURL($marka).'-'.gjeneroURL($modeli).'-'.gjeneroURL($tipi).'-'.gjeneroURL($mshitjeVitiProdhimit).'-'.$mshitjeID.'/' ?>"><img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_MAKINA_SHITJE.$mshitjeFotografi1;?>" alt="<?php echo $gjuha['INDEX_MAKINA_SHITJE_SHITET'].' '.$marka.' '.$modeli.' '.$tipi.' '.$mshitjeKambio.' '.$mshitjeKarburant.' '.$mshitjeVitiProdhimit;?>"></a>
                         <?php if ($mshitjeShitur=='Po'){ echo '<div class="shitur"></div>';}?></a>
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
               </div> <!-- Col end -->
                <?php
        }//fundi while
                ?>
            </div> <!-- makina-shitje Carousel -->
         </div> <!-- Row End -->
      </div> <!-- Container end -->
   </section> <!-- makina-shitje end -->
<?php
                                           }//fundi if > 0
else {

}
?>

