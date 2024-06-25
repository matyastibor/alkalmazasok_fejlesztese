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
login();
?>
<base href="<? echo $bazisurl; ?>" target="_self">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<meta HTTP-EQUIV="Content-Language" content="hu">
 <title>Közműnyilvántartó rendszer - Grand Corporation Kft.</title>
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
 <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
 <script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

 <!--[if lt IE 9]>
   <script src="http//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]--> 
</head>
<body>

<div class="globalWrapperLogin">

<div class="login">
<!--<h1 style="margin-top:-30px;margin-left:-17px;margin-bottom:10px;">&nbsp;</h1>-->
 <form method="post" action="">
  <center><input type="password" name="password" class="inputPretty" <?php if(isset($_GET['error'])) echo 'style="border:1px solid red;float:initial !important;"'; ?> required placeholder="Jelszó" maxlength="10" style="float:initial !important;"> <input type="submit" name="submitLogin" value="" class="loginBtnPretty"></center>
 </form>
</div>

</div> 

</body>
</html>
<?php
mysql_close($kapcsolat);
ob_end_flush();
?>