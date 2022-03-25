
   <section id="ts-service-bg" class="ts-service-bg bg-overlay">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <h2 class="section-title text-white">
                  <?php echo $gjuha['INDEX_SHERBIMET_TONA'];?>
               </h2>
            </div> <!-- Col End -->
         </div> <!-- Row End -->
      </div><!-- Container end -->
   </section> <!-- Service BG end -->

   <section id="ts-service" class="ts-service pb-0">
      <div class="container">
         <div class="row">
            <div class="service-carousel owl-carousel">
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
               <div class="col-md-12">
                  <div class="ts-service-wrapper">
                     <span class="service-img">
                        <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_SHERBIME.$sherbimeFoto;?>" alt="<?php echo $sherbimeEmertimi;?>">
                     </span> <!-- Service Img end -->
                     <div class="service-content">
                        <div class="service-icon">
                           <?php echo $sherbimeIkona;?>
                        </div> <!-- Service icon end -->
                        <h3><a href="#"><?php echo $sherbimeEmertimi;?></a></h3>
                        <p><?php echo $sherbimeTxt;?></p>
                     </div> <!-- Service content end -->
                  </div> <!-- Service wrapper end -->
               </div> <!-- Col end -->
                <?php
        }//fundi while
    }//fundi if > 0
                ?>
            </div> <!-- Service Carousel -->
         </div> <!-- Row End -->
      </div> <!-- Container end -->
   </section> <!-- Service end -->

