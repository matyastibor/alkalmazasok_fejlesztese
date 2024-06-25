<style type="text/css" media="print">
	.top, .menu, #cegKezeloIkonok, .ujMeroGomb, .actions img, .noprint, .footer {
		display: none;
	}

	#oraAdatlap td {
	 padding: 5px;
	}

	h1 {
		margin-bottom: 50px;
	}
</style>
<div class="szuro noprint" style="background-image:url(images/back-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:8px;margin-right:0px;margin-bottom:8px;" onClick="window.location.href='index.php?p=merok';">
     <div class="parameter" style="width:90px;">Vissza</div>
    </div>

<div class="szuro noprint" style="background-image:url(images/print.gif);background-size:auto 55%;background-position:left center;float:right;margin-top:8px;margin-right:10px;margin-bottom:8px;" onClick="window.print();">
     <div class="parameter" style="width:90px;">Nyomtatás</div>
    </div><div class="clear"></div>

<h1>Mérõ adatai</h1>
<?php
loginControll(); 
getMero();
?>
<p style="margin-top:50px;font-size:70%;font-family: Arial, Helvetica, sans-serif;border-top:1px solid #cdcdcd;padding-top:5px;text-indent:5px;">Ez az oldal a <a href="http://grandcorporation.hu" style="color:#fb5500;text-decoration:none;"><b>Grand Corporation Kft.</b></a> rendszerében készült.</p>