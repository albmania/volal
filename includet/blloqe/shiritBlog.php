<?php
//$gjuhaR
$sql = "SELECT  * FROM blog, blog_kategori WHERE blogKategoria=blogKatID AND blogGjuha='$gjuhaR' ORDER BY blogDtPublik DESC LIMIT 3";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
   <section id="ts-news" class="ts-news">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <h2 class="section-title">
                  <?PHP echo $gjuha['BLOG_TITULL_INDEX']; ?>
               </h2>
            </div> <!-- Col End -->
         </div><!-- Row End -->
         <div class="row">
            <div class="col-md-12">
               <div class="news-carousel owl-carousel">
                   
                   <?php
                while ($row = $result->fetch_assoc()) {
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
                   
                   <?php
                }
                           }
?>

               </div><!-- Row end -->
            </div>
         </div><!-- Container end -->
      </div>
   </section> <!-- News end -->

