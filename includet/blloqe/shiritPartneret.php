
   <section id="ts-pertner" class="ts-pertner solid-bg">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="partner-carousel owl-carousel">
                   <?php
$sql="SELECT * FROM prodhues ORDER BY RAND() LIMIT 0, 10";
//echo $sql;
	$result = $conn->query($sql);
		if ($result->num_rows > 0) {	
			 while($row = $result->fetch_assoc()) {
			     $prodhuesID =$row["prodhuesID"];
				 $prodhuesEmri =$row["prodhuesEmri"];
				 $prodhuesLogo =$row["prodhuesLogo"];						 
?>
                   
                  <figure class="partner-item partner-logo">
                     <img class="img-fluid" src="<?php echo $MAIN_WEB_URL.$UP_PRODHUES_LOGO.$prodhuesLogo;?>" alt="<?php echo $prodhuesEmri;?>">
                  </figure> <!-- Figure end -->
                   <?php
             }//fundi while
        }//fundi if
                   ?>
               </div> <!-- Partner carousel end -->
            </div> <!-- Col end -->
         </div> <!-- Row end -->
      </div> <!-- Container end -->
   </section> <!-- Partner end -->

