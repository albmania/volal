
    <div class="ts-top-bar">
      <div class="top-bar-angle">
         <div class="container">
            <div class="row">
               <div class="col-lg-6 col-md-4"></div>
               <div class="col-lg-4 col-md-5">
                  <div class="top-bar-event ts-top">
                     <i class="icon icon-clock"></i><span><?php echo $gjuha['LART_HAPUR'];?></span>
                  </div> <!-- Top Bar Text End -->
               </div> <!-- Col End -->
               <div class="col-lg-2 col-md-3 text-right">
                  <div class="top-bar-social-icon ml-auto">
                     <ul>
                        <li><a href="<?php echo $SOC_FCB;?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="<?php echo $SOC_INSTA;?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="<?php echo $SOC_INSTA2;?>" target="_blank"><i class="fab fa-instagram-square"></i></a></li>
                     </ul>
                  </div> <!-- Social End -->
               </div><!-- Col End -->
            </div> <!-- Row End -->
         </div> <!-- Container End -->
      </div> <!-- Angle Bar End -->
   </div> <!-- Top Bar End -->

   <header class="ts-header header-default">

      <div class="ts-logo-area">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-4 col-sm-4">
                  <a class="ts-logo" href="<?php echo $WEB_URL;?>" class="ts-logo">
                     <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/logo/logo.png" alt="Vol-AL Service">
                  </a>
               </div> <!-- Col End -->
               <div class="col-md-8 col-sm-8 float-right">
                  <ul class="top-contact-info">
                     <li>
                        <span><i class="icon icon-phone3"></i></span>
                        <div class="info-wrapper">
                           <p class="info-title"><?php echo $gjuha['LART_TEL_SERVISI'];?></p>
                           <p class="info-subtitle"><?php echo $NR_TEL_SERVISI_1;?></p>
                        </div>
                     </li> <!-- li End -->
                     <li>
                        <span><i class="icon icon-phone3"></i></span>
                        <div class="info-wrapper">
                           <p class="info-title"><?php echo $gjuha['LART_TEL_SHITJE'];?></p>
                           <p class="info-subtitle"><?php echo $NR_TEL_SHITJE_1;?></p>
                        </div>
                     </li> <!-- Li End -->
                     <li>
                        <a href="<?php echo $WEB_URL.$URL_KONTAKTET;?>" class="btn btn-primary"><?php echo $gjuha['LART_NA_KONTAKTONI'];?></a>
                     </li> <!-- Li End -->
                  </ul> <!-- Contact info End -->
               </div> <!-- Col End -->
            </div> <!-- Row End -->
         </div> <!-- Container End -->
      </div> <!-- Logo End -->
	  
      <div class="header-angle">
         <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                  aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
               </button><!-- End of Navbar toggler -->
               <div class="collapse navbar-collapse justify-content-end ts-navbar" id="navbarSupportedContent">
                  <ul class="navbar-nav">
                      <?php
					$sql="SELECT * FROM menu_kryesore WHERE menukGjuha='$gjuhaR' AND menukAktiv='Po' ORDER BY menukRadhe ASC";
					//echo $sql;
							$result = $conn->query($sql);
								if ($result->num_rows > 0) {
									$i = 1;
									while($row = $result->fetch_assoc()) {
										 $menukTitull=$row["menukTitull"];
										 $menukUrl=$row["menukUrl"];
										 $menukBlank =$row["menukBlank"];
										 $menukRadhe =$row["menukRadhe"];
										 $menukID =$row["menukID"];

										 
				?>
                     <li class="nav-item dropdown active">
                        <a class="nav-link" href="<?php echo $menukUrl ;?>" target="<?php echo $menukBlank;?>">
                        <?php echo $menukTitull;?>
                            <span class="ts-indicator"><i class="fa fa-angle-down"></i></span>
                     </a>
                         <?php
					$sql2="SELECT * FROM menu_dytesore WHERE menudGjuha='$gjuhaR' AND menudAktiv='Po' AND menudKryesore=$menukID ORDER BY menudRadhe ASC";
					//echo $sql;
							$result2 = $conn->query($sql2);
								if ($result2->num_rows > 0) {
									$i = 1;
				?>
                        <ul class="dropdown-menu ts-dropdown-menu">
                            <?php
                                    while($row2 = $result2->fetch_assoc()) {
										 $menudTitulli=$row2["menudTitulli"];
										 $menudUrl=$row2["menudUrl"];
										 $menudBlank =$row2["menudBlank"];
										 $menudRadhe =$row2["menudRadhe"];
										 $menudID =$row2["menudID"];
                                        ?>
                           <li><a class="active" href="<?php echo $menudUrl ;?>" target="<?php echo $menudBlank;?>"><?php echo $menudTitulli;?> </a></li>
                            
                            <?php
                                    }//fundi while row2
                                    echo '</ul>';
                                }//fundi if result2
                                        else {
                                            echo '</li>';
                                        }
                            ?>

                        
                         <?php
                                    }
                                }
                      ?>
                     </li><!-- End Dropdown -->

                  </ul> <!-- End Navbar Nav -->
               </div> <!-- End of navbar collapse -->
<?php
                /*
               <div class="header-cart">
                  <div class="cart-link">
                     <form action="#">
                           <button type="button"><i class="icon icon-search show"></i></button>
                        <button><i class="icon icon-cross"></i></button>
                        <div class="search-box">
                           <input type="search" name="search" id="search" placeholder="Type here and Search...">
                        </div>
                     </form>
                  </div>
               </div> <!-- End Cart -->
*/
                ?>
            </nav> <!-- End of Nav -->
         </div> <!-- End of Container -->
      </div> <!-- End of Header Angle-->

   </header> <!-- End of Header area-->
