<?php
$GLOBALS['MAIN_WEB_URL']='https://volalservice.al/';
//$GLOBALS['WEB_URL']='http://pgfront.amg/';
$GLOBALS['WEB_URL_EN']='https://volalservice.al/en/';
$GLOBALS['WEB_URL_FRONT']='https://volalservice.al/';
$GLOBALS['SOC_FCB']='https://www.facebook.com/volalservice';
$GLOBALS['SOC_INSTA']='https://instagram.com/volalservice';
$GLOBALS['SOC_INSTA2']='https://instagram.com/volalauto';
$GLOBALS['SOC_TW']='http://twitter.com/';
$GLOBALS['HARTA_API']= 'pk.eyJ1IjoiYWxibWFuaWEiLCJhIjoiY2tjZ3ptaDdkMHd4cjJ6bng0czFibXZrdSJ9.UlXsH4qWnveo8cZGSV-ykg';
$GLOBALS['GET_URI']= $_SERVER['REQUEST_URI'];

$GLOBALS['FOOTER_TEL']='+355 69 20 35 032';
$GLOBALS['FOOTER_EMAIL']='kontakt@volalservice.al';
$GLOBALS['FOOTER_ADRESA']='Autostrada Tirane - Durres, Km.14';

$GLOBALS['NR_TEL_SERVISI_1']='+355 69 20 35 032';
$GLOBALS['NR_TEL_SERVISI_2']='+355 69 84 29 720';
$GLOBALS['ADRESA_SERVISI']='Autostrada Tirane - Durres, Km. 14, Vore';
$GLOBALS['EMAIL_SERVISI']='servisi@volalservice.al';
$GLOBALS['NR_TEL_SHITJE_1']='+355 67 52 62 755';
$GLOBALS['NR_TEL_SHITJE_2']='+355 69 20 65 101';
$GLOBALS['ADRESA_SHITJE']='Autostrada Tirane - Durres, Km. 15, Vore';
$GLOBALS['EMAIL_SHITJE']='makina@volalservice.al';
$GLOBALS['NR_TEL_PJESE_1']='+355 69 20 65 102';
$GLOBALS['NR_TEL_PJESE_2']='+355 42 42 42 14';
$GLOBALS['ADRESA_PJESE']='Autostrada Tirane - Durres, Km. 14, Vore';
$GLOBALS['EMAIL_PJESE']='pjese@volalservice.al';


$GLOBALS['URL_ERROR404']='/error-404.php';
// Imazhet per background neper faqe
$GLOBALS['SHERBIMET_BG']='asetet/img/sfonde/liqeni-varka.jpg';

// URL per ngarkimet (uploadet)
$GLOBALS['UP_INDEX_PSENE']='ngarkime/index/psene/';
$GLOBALS['UP_INDEX_SLIDE']='ngarkime/slide/';
$GLOBALS['UP_SHERBIME']='ngarkime/sherbime/';
$GLOBALS['UP_MAKINA_SHITJE']='ngarkime/makina/';
$GLOBALS['UP_MAKINA_LOGO']='ngarkime/marka/logo/';
$GLOBALS['UP_PRODHUES_LOGO']='ngarkime/prodhues/';
$GLOBALS['UP_BLOG']='ngarkime/blog/';
$GLOBALS['UP_FAQ']='ngarkime/faqe/';


/****************/
// Info baze per header
$GLOBALS['HEADER_ZV_KERKO']= array('&Euml', '&Ccedil', '&euml', 'ë', 'ç','&ccedil','&quot','&ldquo','&bdquo','&ndash','&nbsp','<strong>', '</strong>', '', '&', '&amp');
$GLOBALS['HEADER_ZV_NDRYSHO']= array('E', 'C', 'e', 'e', 'c','c',' ','','','',' ','','',', ','','');
//$GLOBALS['URL']='';


/**********************************************************************************************************
 * ###################################################################################################### *
 * Variablat per header te faqes 
 * titull, keywords, description, og, twitter
 * ###################################################################################################### *
***********************************************************************************************************/

/************************************************************************************/
// DREJTORITE
/************************************************************************************/

if (isset($_GET['blogID'])){
				$blogID=checkNr($_GET['blogID']);
				$sql =  "SELECT  * FROM blog, blog_kategori WHERE blogKategoria=blogKatID AND blogGjuha='$gjuhaR' AND blogID=$blogID";
				//echo $sql;
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result)) {
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
							
							$varTitle=$blogTitulli.' - '.$gjuha['TITULLI_FAQES'];
							$varDesc1=substr(strip_tags($blogTitulli.' '.$blogKatEmertimi),0,150);
							$varDesc=preg_replace("/[^?\,\!@&\sA-Za-z0-9_]/", "",str_replace($HEADER_ZV_KERKO, $HEADER_ZV_NDRYSHO, $varDesc1));
							$varKeywds=$blogTitulli.','.$blogKatEmertimi.','.$gjuha['KEYWDS'];
							$varImg=$MAIN_WEB_URL.$UP_BLOG.$blogFoto1;
							
						} //fundi while
					}//fundi if result >
}//fundi if drid
		

/************************************************************************************/
// FAQET
/************************************************************************************/
elseif (isset($_GET['faqeid'])){
				$faqeID=checkNr($_GET['faqeid']);
				$sql = "SELECT * from faqet, faqet_kategoria
					WHERE faqeID = $faqeID AND faqeKategoria=faqekatID AND faqeGjuha='$gjuhaR'";
				//echo $sql;
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result)) {
							$faqeTxt=$row['faqeTxt'];
							$faqeFoto=$row['faqeFoto'];
							list($faqeFoto1) = explode(',',$faqeFoto);						
							
							$varTitle=$row['faqeTitulli'].' - '.$row['faqekatEmertimi'].' - '.$gjuha['TITULLI_FAQES'];
							$varDesc1=substr(strip_tags($faqeTxt),0,150);
							$varDesc=preg_replace("/[^?\,\!@&\sA-Za-z0-9_]/", "",str_replace($HEADER_ZV_KERKO, $HEADER_ZV_NDRYSHO, $varDesc1));
							$varKeywds=$row['faqeTitulli'].','.$row['faqekatEmertimi'].','.$gjuha['KEYWDS'];
							$varImg=$MAIN_WEB_URL.$UP_FAQET_FOTO.$faqeFoto1;
									} //fundi while
					}//fundi if result >
}//fundi if faqeid											

/************************************************************************************/
// HARTA
/************************************************************************************/
							
							



/************************************************************************************/
// ELSE
/************************************************************************************/
		else {
			$varTitle=$gjuha['TITULLI_FAQES'];
			$varDesc=$gjuha['DESC'];
			$varKeywds=$gjuha['KEYWDS'];
			$varImg=$MAIN_WEB_URL.'asetet/imgHeader/';
		}					
							



?>