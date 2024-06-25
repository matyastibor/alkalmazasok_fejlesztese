<?php
session_start();
ob_start();

//url kezelés
$subdir  = substr(realpath(dirname(__FILE__)), strlen(realpath($_SERVER['DOCUMENT_ROOT'])));
$tmp_array = explode('?', trim($_SERVER['REQUEST_URI']));
$uri = str_replace($subdir, '', $tmp_array[0]);
$uri = ltrim($uri, '/');
$URIParts = explode("/", $uri);

include("kapcsolat.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="hu">
<head>
<?php
include('functions.php');
?>
<base href="<?php echo $bazisurl; ?>" target="_self">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<meta HTTP-EQUIV="Content-Language" content="hu">
 <title>Közműnyilvántartó rendszer</title>
 <meta name="description" content=""/>
 <meta name="keywords" content=""/>
 <META name="Copyright" content="">
 <META name="Author" content="web2u">
 <meta name="robots" content="noindex">
 <META http-equiv="pragma" content="no-cache">
 <META http-equiv="cache-control" content="no-cache">
 <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
 
 <!-- CSS -->
 <link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>

 <!-- JQuery --> 
 <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
 <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

 <!-- PrettyPhoto -->  	
  <link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
  <script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
 <!-- PrettyPhoto -->
 
 <!--[if lt IE 9]>
   <script src="http//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]-->

 <script src="js/kozmu.js" type="text/javascript" charset="iso-8859-2"></script>

 <script>
  $(document).ready(function(){
	  
	  $(".eloado").click(function() {		
		$(".eloado").removeClass("aktiv");	 
		$(this).addClass("aktiv");
	  }); 

    //Close button:
		
		$(".close").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(400);
				});
				return false;
			}
		);

    // Alternating table rows:
		
		$('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows

    // Check all checkboxes when the one in a table head is checked:
		
		$('.check-all').click(
			function(){
				$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
			}
		);	  
	  
  });
		
  </script>
    <style type="text/css" media="print">	  
	  div.page
      {
        page-break-after: always;
        page-break-inside: avoid;
      }
    </style>  
 
</head>
<body>

<div class="globalWrapper">

<div class="uveg">
<div class="top">
	<h2>Közműnyilvántartó rendszer</h2>
</div>

<div class="menu">
 <a href="?p=kezdolap" class="<?php aktivMenupont("kezdolap"); ?>" id="kezdolap">Fogyasztás</a>
 <a href="?p=telephelyek" class="<?php aktivMenupont("telephelyek"); ?>" id="telephelyek">Telephelyek</a>
 <a href="?p=kozmuvek" class="<?php aktivMenupont("kozmuvek"); ?>" id="szolgaltatasok">Szolgáltatások</a>
 <a href="?p=cegek" class="<?php aktivMenupont("cegek#ceg"); ?>" id="ugyfelek">Ügyfelek</a>
 <a href="?p=merok" class="<?php aktivMenupont("merok#mero"); ?>" id="merok">Mérők</a>
 <a href="?p=export" class="<?php aktivMenupont("export"); ?>" id="adatbazis">Adatbázis</a> 
 <a href="?logout" class="logout" id="logout">Kilépés</a>  
</div>

<div class="content"><div class="margo">
<?php
contentLoading();
?>
</div></div>

<div class="footer"><div class="margo">
&nbsp;<br>
&copy; 2024
</div></div>

<div class="clear"></div>
</div>

</div>

<div id="toTop" onclick="var body=$('html, body');body.stop().animate({scrollTop:0},1000,'swing');"><img src="images/top-arrow.png" style="width:20px;margin:5px;margin-top:8px;"></div>
<script>
 $(window).scroll(function(){
    if($(window).scrollTop()>=300) { 
	 $("#toTop").fadeIn();
	} else {	
	 $("#toTop").fadeOut();		 
	}
});
</script> 

<script type="text/javascript" charset="utf-8">
   $("a[rel^='prettyPhoto']").prettyPhoto({
            theme:'light_square', /* dark_square, light_square, dark_rounded, light_rounded, facebook */
			social_tools: false,
			keyboard_shortcuts: false,
            deeplinking: false 
  });
</script>
</body>
</html>
<?php
mysql_close($kapcsolat);
ob_end_flush();
?>