<?php
include('includet/db.php');
// Scripti per gjuhet
session_start();
header('Cache-control: private'); // IE 6 FIX
if (strpos($_SERVER['REQUEST_URI'], "/en/") !== false){
	$gjuhaR = 'en';
}
else if (strpos($_SERVER['REQUEST_URI'], "/en/") !== true){
	$gjuhaR = 'sq';
}
else if(isset($_GET['gjuha']))
{
$gjuhaR = $_GET['gjuha'];

// regjistron session dhe vendos cookie
$_SESSION['gjuha'] = $gjuhaR;

setcookie('gjuha', $gjuhaR, time() + (3600 * 24 * 30));
}
else if(isset($_SESSION['gjuha']))
{
$gjuhaR = $_SESSION['gjuha'];
}
else if(isset($_COOKIE['gjuha']))
{
$gjuhaR = $_COOKIE['gjuha'];
}
else
{
$gjuhaR = 'sq';
}

switch ($gjuhaR) {
  case 'en':
  $gjuha_file = 'gjuha.en.php';
  break;

  case 'sq':
  $gjuha_file = 'gjuha.sq.php';
  break;

  default:
  $gjuha_file = 'gjuha.sq.php';
}

include_once 'includet/gjuhet/'.$gjuha_file;
include('includet/funksione.php');
include('includet/variabla.php');
// Fundi scriptit per gjuhet
?>
<!doctype html>
<html lang="<?php echo $gjuhaR;?>">

<head>
   <!-- Basic Page Needs =====================================-->
   <meta charset="utf-8">

   <!-- Mobile Specific Metas ================================-->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <!-- Site Title- -->
    <title><?php echo $varTitle;?></title>
    <meta name="description" content="<?php echo $varDesc;?>">
	<meta name="keywords" content="<?php echo $varKeywds;?>">
	<meta property="og:url" content="<?php echo 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];?>" />
	<meta property="og:type" content="Website" />
	<meta property="og:title" content="<?php echo $varTitle;?>" />
	<meta property="og:description" content="<?php echo $varDesc;?>" />
	<meta property="og:image" content="<?php echo $varImg;?>" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?php echo $varTitle;?>" />
	<meta name="twitter:description" content="<?php echo $varDesc;?>" />
	<meta name="twitter:image" content="<?php echo $varImg;?>" />

   <!-- CSS
   ==================================================== -->

   <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/animate.css">

   <!-- IcoFonts -->
   <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/icofonts.css">
   <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/automobil_icon.css">

   <!-- Bootstrap -->
   <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/bootstrap.min.css">

   <!-- Owl Carousel -->
   <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/owlcarousel.min.css">

   <!-- Style -->
   <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/style.css">

   <!-- Responsive -->
   <link rel="stylesheet" href="<?php echo $MAIN_WEB_URL;?>asetet/css/responsive.css">

   <script src="https://kit.fontawesome.com/b8833fc4c0.js" crossorigin="anonymous"></script>
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->