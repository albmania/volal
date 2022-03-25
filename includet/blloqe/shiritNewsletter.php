
   <section id="ts-newsletter" class="ts-newsletter">
      <div class="container-fluid no-padding">
         <div class="row">
            <div class="col-md-6 align-self-center">
               <div class="box-skew-area-left bg-red">
                  <div class="box-skew-sm-left">
                     <h2 class="column-title text-white">
                        <?php echo $gjuha['NSL_ABONOHU_TXT'] ;?>
                     </h2>
                     <form class="contactMe newsletter-form" action="#" method="POST" enctype="multipart/form-data">
                        <section>
                           <div class="form-row">
                              <div class="col-md-12 newsletter-box">
                                 <input type="email" name="email" data-displayname="E-mail" class="field" placeholder="<?php echo $gjuha['NSL_ABONOHU_UR_EMAIL'] ;?>" required>
                                 <button class="btn btn-bordered" type="submit" data-sending="Sending..."><i class="fa fa-send"></i></button>
                              </div>
                           </div> <!-- Form Row End -->    
                           <div class="msg"></div>
                        </section> <!-- Ection end -->
                     </form><!-- END copy section:Service Contact Form -->
                  </div> <!-- Newsletter Content end -->
               </div> <!-- Newsletter Left end -->
            </div> <!-- Col End -->
            <div class="col-md-6">
               <div class="box-skew-area-right">
                  <div class="box-skew-sm-right">
                        <img src="<?php echo $MAIN_WEB_URL;?>asetet/images/we_offer_img.jpg" alt="">
                     <h2 class="column-title text-white no-border">
                        <small><?php echo $gjuha['NSL_DJATHTAS_TXT1'];?></small>
                        <?php echo $gjuha['NSL_DJATHTAS_TXT2'];?>
                     </h2>
                     <a href="/kontaktet/" class="btn btn-primary"><?php echo $gjuha['NSL_DJATHTAS_BUTON'];?></a>
                  </div> <!-- Newsletter content end -->
               </div> <!-- Newsletter right end -->
            </div> <!-- Col end -->
         </div> <!-- Row End -->
      </div> <!-- Container fluid end -->
   </section> <!-- Newsletter End -->

