
    <div class="ts-slider-area owl-carousel">
<?php

    $sql = "SELECT * from slide
			WHERE slideAktiv='Po' AND slideGjuha='$gjuhaR' ORDER BY slideRadha ASC";
	//echo $sql;
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
			$slideID=$row['slideID'];
			$slideTxt1 =$row['slideTxt1'];
            $slideTxt2 =$row['slideTxt2'];
            $slideTxt3 =$row['slideTxt3'];
			$slideButonTxt=$row['slideButonTxt'];
            $slideLink=$row['slideLink'];
            $slideTarget=$row['slideTarget'];
			$slideFoto=$row['slideFoto'];
?>        
      <div class="slider-items slider-overlay" style="background: url(<?php echo $MAIN_WEB_URL.$UP_INDEX_SLIDE.$slideFoto;?>)">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-lg-8 col-md-12">
                  <div class="slider-content">
                     <h1>
                        <small><?php echo $slideTxt1;?></small>
                        <?php echo $slideTxt2;?>
                     </h1>
                     <p class="slider-desc"><?php echo $slideTxt3;?></p>
                      <?php
                        if ($slideButonTxt!=NULL){
                            ?>
                     <a href="<?php echo $slideLink;?>" target="<?php echo $slideTarget;?>" class="btn btn-primary"><?php echo $slideButonTxt;?></a>
                      <?php 
                        }
            ?>
                  </div> <!-- Slider Content End -->
               </div> <!-- Col End -->
            </div> <!-- Row ENd -->
         </div> <!-- Container End -->
      </div> <!-- 1st Slider -->
<?php
        }//fundi while
    }//fundi if > 0
            ?>
   </div> <!-- Slider Area End -->

    <?php include('includet/blloqe/indexPoshteSlider.php');?>
