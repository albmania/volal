<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

/***********************************************************************/
// Vendos timezone ne Tirane
/***********************************************************************/
date_default_timezone_set("Europe/Tirane");//Vendosim timezone

/*  TE NDRYSHME
    strtolower() - converts a string to lowercase.
    lcfirst() - converts the first character of a string to lowercase.
    ucfirst() - converts the first character of a string to uppercase.
    ucwords() - converts the first character of each word in a string to uppercase.
*/

/***********************************************************************/
// Verifiko nese eshte NR dhe pastroje nese nuk eshte
/***********************************************************************/
function checkNr($ID){
	return preg_replace('/[^0-9]/', '', $ID);
}

/***********************************************************************/
// Verifiko nese eshte tekst dhe pastroje nese nuk eshte
/***********************************************************************/
function checkTxt($TXT){
	return preg_replace('/[^a-zA-Z]/', '', $TXT);
}

/***********************************************************************/
// Verifiko nese eshte tekst + numer dhe pastroje nese nuk eshte
/***********************************************************************/
function checkTxtNr($TXT){
	return preg_replace('/[^0-9a-zA-Z]/', '', $TXT);
}

/***********************************************************************/
// Funksion per te nxjerre emrin apo cdo informacion tjeter per ne header
// kur sql statement eshte poshte vendit ku duhet te shfaqet kjo
// psh: nxirrEmrin($conn, 'shteteID, shteteEmri', 'shtete', 'shteteID='.$shtetID, 'shteteEmri')
/***********************************************************************/
function nxirrEmrin($conn, $kolona, $tabela, $where, $duaTeNxjerr){
	$sql = "SELECT $kolona FROM $tabela WHERE $where";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		 while($row = mysqli_fetch_assoc($result)) {
			 echo $row[''.$duaTeNxjerr.''];
			 
		 }
	}
}


/***********************************************************************/
// Ka raste qe me versionin e pare nuk nxjerr ne rregull tekstin
// zakonisht ku kombinohet me funksionin e gjenerimit te url
/***********************************************************************/
function nxirrEmrin2($conn, $kolona, $tabela, $where, $duaTeNxjerr){
	$sql = "SELECT $kolona FROM $tabela WHERE $where";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		 while($row = mysqli_fetch_assoc($result)) {
			 return $row[''.$duaTeNxjerr.''];
			 
		 }
	}
}

/***********************************************************************/
// Funksion per te nxjerre totalin e rreshtave per disa tabela db
// Simple
/***********************************************************************/
function saJane($conn, $tabela, $kolona){
	$query = "SELECT COUNT($kolona) FROM $tabela";
	$result = mysqli_query($conn,$query);
	$rows = mysqli_fetch_row($result);
	return $rows[0];
    mysqli_free_result($result);
}
/***********************************************************************/
// Funksion per te nxjerre totalin e rreshtave per disa tabela db
// conditional
/***********************************************************************/
function saJaneCond($conn, $tabela, $kolona, $where){
	$query = "SELECT COUNT($kolona) FROM $tabela WHERE $where";
	$result = mysqli_query($conn,$query);
	$rows = mysqli_fetch_row($result);
	echo $rows[0];
	mysqli_free_result($result);

}
/***********************************************************************/
// Funksion per te nxjerre totalin e rreshtave per disa tabela db
// conditional pa echo
/***********************************************************************/
function saJaneCond2($conn, $tabela, $kolona, $where){
	$query = "SELECT COUNT($kolona) FROM $tabela WHERE $where";
	//echo $query;
	$result = mysqli_query($conn,$query);
	$rows = mysqli_fetch_row($result);
	return $rows[0];
	mysqli_free_result($result);

}

/***********************************************************************/
// Merr URL Origjines qe me pas ta perdoresh me funkcionin e meposhtem
// https://stackoverflow.com/questions/6768793/get-the-full-url-in-php
/***********************************************************************/
function url_origin( $s, $use_forwarded_host = false ) {
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
	}
// gjeneron url e paster bazuar ne funksionin me siper
function full_url( $s, $use_forwarded_host = false ) {
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}


/***********************************************************************/
// Gjenerimi i butonave share
/***********************************************************************/
/*function butonatShare($linku='/', $gjuhaShare, $MAIN_WEB_URL=''){	
?>	
					<section class="socials socials--gray share-link">
						<hr />
						<p><?php echo $gjuhaShare;?></p>
						<div class="social-item">
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $linku;?>" target="_blank"><img src="<?php echo $MAIN_WEB_URL;?>asetet/img/facebook.png" alt="facebook"></a>
						</div>
						<div class="social-item">
							<a href="https://twitter.com/home?status=<?php echo $linku;?>" target="_blank"><img src="<?php echo $MAIN_WEB_URL;?>asetet/img/twitter.png" alt="Twitter"></a>
						</div>
						<div class="social-item">
							<a href="https://api.whatsapp.com/send?phone=whatsappphonenumber&amp;text=<?php echo $linku;?>" data-action="share/whatsapp/share" target="_blank"><img src="<?php echo $MAIN_WEB_URL;?>asetet/img/whatsapp.png" alt="Whatsapp"></a>
						</div>
					</section>
<?php	
}
*/
/***********************************************************************/
// Funksion per shfaqjen e bannerave sipas pozicionit
/***********************************************************************/
function shfaqBanner($conn,$vendi,$gjuhaR='sq', $WEB_URL=''){
	$sql="SELECT * FROM bannera WHERE bannerVendi='$vendi' AND bannerAktiv='Po' AND bannerGjuha='$gjuhaR' ORDER BY bannerRadha ASC";
//echo $sql;
		$result = $conn->query($sql);
			if ($result->num_rows > 0) {	
				 while($row = $result->fetch_assoc()) {
					 $bannerEmri=$row["bannerEmri"];
					 $bannerImg=$row["bannerImg"];
					 $bannerLink =$row["bannerLink"];
					 $bannerBlank =$row["bannerBlank"];
					 if ($bannerBlank=='Po'){ echo $blank='target="_blank"';}
					 else { $blank=''; }
					 
?>
	<a href="<?php echo $bannerLink;?>"<?php echo $blank;?>>
		<img src="<?php echo $MAIN_WEB_URL;?><?php echo $bannerImg;?>" alt="<?php echo $bannerEmri;?>">
	</a>
<?php
} //fundi while
			}//fundi if result > 0
}//fundi function shfaqBanner

/***********************************************************************/
// Gjenerim URL per Link
/***********************************************************************/
function gjeneroURL($rregEmri){
	
	$search = array('ë', 'Ë', 'Ç', 'ç', '&amp','"'); // Keto ketu
    $replace = array('e', 'E', 'C', 'c','dhe', ''); // i zenedesojme me keto
    $str = str_replace( $search, $replace, $rregEmri ); // duke perdorur kete funksionin ketu
	
	$str2 = preg_replace('/[^a-z0-9--]/', '-', strtolower($str)); // pastaj heqim cdo gje pervesh shkronjave dhe nr, dhe -
	return str_replace('--','-',$str2); // dhe ne fund, zevendesojme -- me -
}


/************************************************************/
//Funksioni i Pagination
function faqosja($item_per_page, $current_page, $total_records, $total_pages, $page_url)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="faqosja clearfix">';
        
        $right_links    = $current_page + 5; 
        $previous       = $current_page - 1; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
        if($current_page > 1){
			$previous_link = ($previous==0)?1:$previous;
            $pagination .= '<li class="first"><a href="'.$page_url.'?faqja=1" title="Faqja e Pare"><i class="fas fa-fast-backward"></i> Ne Krye</a></li>'; //first link
            $pagination .= '<li><a href="'.$page_url.'?faqja='.$previous_link.'" title="Faqja Meparshme"><i class="fas fa-hand-point-left"></i> Mbrapa</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="'.$page_url.'?faqja='.$i.'">'.$i.'</a></li>';
                    }
                }   
            $first_link = false; //set first link to false
        }
        
        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active"><a href="#">'.$current_page.'</a></li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active"><a href="#">'.$current_page.'</a></li>';
        }else{ //regular current link
            $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="'.$page_url.'?faqja='.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
				$next_link = $current_page + 1;
                $pagination .= '<li><a href="'.$page_url.'?faqja='.$next_link.'" title="Faqja Tjeter">Para <i class="fas fa-hand-point-right"></i></a></li>'; //next link
                $pagination .= '<li class="last"><a href="'.$page_url.'?faqja='.$total_pages.'" title="Faqja Fundit">Ne Fund <i class="fas fa-fast-forward"></i></a></li>'; //last link
        }
        
        $pagination .= '</ul>'; 
    }
    return $pagination; //return pagination links
}

//Funksioni i Pagination
/************************************************************/

/************************************************************/
//Funksioni per te fshehur email / obfuscation
function fshihEmail($email){ 
	$p = str_split(trim($email));
    $new_mail = '';
    foreach ($p as $val) {
        $new_mail .= '&#'.ord($val).';';
    }
    return $new_mail;
}
//Funksioni per te fshehur email / obfuscation
/************************************************************/

/************************************************************/
// Funksion per limitim karakteresh
    function limitoShkronja($ke,$sa){
        return (strlen($ke) > $sa) ? substr($ke, 0, $sa) . '...' : $ke;
    }
?>