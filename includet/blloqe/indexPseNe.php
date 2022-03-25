
   <section id="ts-feature" class="ts-feature pb-0">
       <?php
				// Marrim te dhenat e faqes me ID = 10 => Keshilli bashkiak	
				$sql="SELECT * FROM index_psene WHERE iPseNeGjuha='$gjuhaR'";
				//echo $sql;
			$result = $conn->query($sql);
				if ($result->num_rows > 0) {	
					 while($row = $result->fetch_assoc()) {
				     
                         $iPseNeB1Titull=$row["iPseNeB1Titull"];
                         $iPseNeB1Txt=$row["iPseNeB1Txt"];
                         $iPseNeB1Ikona=$row["iPseNeB1Ikona"];
					 
                         $iPseNeB2Titull=$row["iPseNeB2Titull"];
                         $iPseNeB2Txt=$row["iPseNeB2Txt"];
                         $iPseNeB2Ikona=$row["iPseNeB2Ikona"];
					 
                         $iPseNeB3Titull=$row["iPseNeB3Titull"];
                         $iPseNeB3Txt=$row["iPseNeB3Txt"];
                         $iPseNeB3Ikona=$row["iPseNeB3Ikona"];
					 
                         $iPseNeB4Titull=$row["iPseNeB4Titull"];
                         $iPseNeB4Txt=$row["iPseNeB4Txt"];
                         $iPseNeB4Ikona=$row["iPseNeB4Ikona"];
					 
                         $iPseNeFoto =$row["iPseNeFoto"];
					
				?>
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <h2 class="section-title">
                  <?php echo $gjuha['IPN_PSE_NE'];?>
               </h2>
            </div><!-- Col end -->
         </div><!-- Row End -->
         <div class="row">
            <div class="col-lg-8 col-md-12">
               <div class="row">
                  <div class="col-md-6">
                     <div class="ts-feature-wrapper">
                        <div class="feature-single">
                           <span class="feature-icon">
                              <?php echo $iPseNeB1Ikona;?>
                           </span><!-- feature icon -->
                           <div class="feature-content">
                              <h3><?php echo $iPseNeB1Titull;?></h3>
                              <p><?php echo $iPseNeB1Txt;?></p>
                           </div><!-- feature content end -->
                        </div><!-- feature single end -->
                     </div><!-- feature wrapper end -->
                  </div><!-- Col end -->
                  <div class="col-md-6">
                     <div class="ts-feature-wrapper">
                        <div class="feature-single">
                           <span class="feature-icon">
                              <?php echo $iPseNeB2Ikona;?>
                           </span><!-- feature icon -->
                           <div class="feature-content">
                              <h3><?php echo $iPseNeB2Titull;?></h3>
                              <p><?php echo $iPseNeB2Txt;?></p>
                           </div><!-- feature content end -->
                        </div><!-- feature single end -->
                     </div><!-- feature wrapper end -->
                  </div><!-- Col end -->
               </div><!-- Content Row End -->
               <div class="gap-35"></div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="ts-feature-wrapper">
                        <div class="feature-single">
                           <span class="feature-icon">
                              <?php echo $iPseNeB3Ikona;?>
                           </span><!-- feature icon -->
                           <div class="feature-content">
                              <h3><?php echo $iPseNeB3Titull;?></h3>
                              <p><?php echo $iPseNeB3Txt;?></p>
                           </div><!-- feature content end -->
                        </div><!-- feature single end -->
                     </div><!-- feature wrapper end -->
                  </div><!-- Col end -->
                  <div class="col-md-6">
                     <div class="ts-feature-wrapper">
                        <div class="feature-single">
                           <span class="feature-icon">
                              <?php echo $iPseNeB4Ikona;?>
                           </span><!-- feature icon -->
                           <div class="feature-content">
                              <h3><?php echo $iPseNeB4Titull;?></h3>
                              <p><?php echo $iPseNeB4Txt;?></p>
                           </div><!-- feature content end -->
                        </div><!-- feature single end -->
                     </div><!-- feature wrapper end -->
                  </div><!-- Col end -->
               </div><!-- Content Row End -->
            </div><!-- Col End -->
         </div><!-- Row End -->
      </div><!-- Container end -->
      <div class="feature-img">
         <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_INDEX_PSENE.$iPseNeFoto;?>" alt="<?php echo $gjuha['IPN_PSE_NE'];?>">
      </div><!-- feature Img -->
       <?php 
					 } 
				}
                   ?>
   </section><!-- Ts feature end -->

