<?php
header("Content-Type: text/html; charset=iso-8859-2"); 
session_start();
include('kapcsolat.php');
include('functions.php');
?>
<link href="css/stilus_kozmu_print.css" rel="stylesheet" type="text/css"  media="print"/>
	
	<style type="text/css" media="print">	
	div.page
    {
        page-break-after: always;
        page-break-inside: avoid;
    }
	
	.footer, .noprint {
		display: none;
	}

	#keszitette {
		margin: 50px 0px;
		font-size: 70%;
		font-family: Arial, Helvetica, sans-serif;
		border-top: 1px solid #cdcdcd;
		padding-top: 5px;
		text-indent: 5px;
	}	
    </style>
<script>
/*
	A számláló CSAK! ebben a fájlban helyezkedhet el
*/
	var cegIndex = 0;
	mutat = 1;	
	cegek = $(".cegekMenu").children("a");
	maxCeg = 0;
	$(cegek).each(function() {
	 if(maxCeg==0) cegIndex = $(this).attr("href"); 	
	 maxCeg++;			
	});
	
	//alert(maxCeg);	
	
$(document).ready(function(){
	$(".footer2").hide();	
	
	$("#mutato").html('<div class="szamlalo">'+mutat+'/'+maxCeg+'</div>');
	
	$(".cegekMenu a").click(function(e){
		   e.preventDefault(); // Öröklött függvények letiltása
		   
		   // CSS módosítások
		   $(".cegekMenu a").removeClass("aktiv");	 
		   $(this).addClass("aktiv");
		   
		   // Mutató módosítása
		   mutat = $(this).attr("id");
		   $("#mutato").html('<div class="szamlalo">'+mutat+'/'+maxCeg+'</div>');
		   
		   // Információk mutatása
		   $(".cegTabla").hide();
		   var index = $(this).attr("href");
		   $("#ceg"+index).show();
		   cegIndex = index;
		   
		   // Nyomtatási képhez
		   $('.cegTabla').removeClass('toPrint');
		   $('#ceg'+index).addClass('toPrint');
		   $('#ceg'+index).removeClass('page');
	});
	
	// Összesítõ hóválasztó
	$("#selectTable").click(function(e){
		e.stopPropagation();
		$('#honapok').toggle();
		$('#modok').hide();
	});
	$("#honapok a").click(function(e){
		e.stopPropagation();
		$("#hoValaszto").html($(this).text());
	});
	
	// Összesítõ havi vagy éves nézet
	$("#selectModTable").click(function(e){
		e.stopPropagation();
		$('#modok').toggle();
		$("#honapok").hide();
	});	
	$("#modok div").click(function(e){
		e.stopPropagation();
		$("#modValaszto").html($(this).text());
		$("#honapok").hide();
		$('.cegTabla').addClass('page');
		$('#ceg0').removeClass('page');
		$('.cegTabla').addClass('toPrint');
	});
	
	$("#haviOssz").click(function(){
		$('#modok').hide();
		$('#haviOsszes').show();
		$('#evesOsszes').hide();
		$('#hoValaszto').show();
	});
	
	$("#eviOssz").click(function(){
		$('#modok').hide();
		$('#haviOsszes').hide();
		$('#evesOsszes').show();
		$('#hoValaszto').hide();
	});
	
	$("#honapok a").click(function(){
		$("#honapok a").removeClass("aktiv");
		$(this).addClass("aktiv");
	});
	
	$("#modok div").click(function(){
		$("#modok div").removeClass("aktiv");
		$(this).addClass("aktiv");
	});
	
});

function mutatHo(ho) {	
	$(".haviOsszesito").hide();
	$("#ho_"+ho).show();
	$("#honapok").hide();
	$("#modok").hide();
}

function ellenoriz(ny,z) {
 var nyito = document.getElementById(ny).innerHTML;
 nyito = nyito*1;
 if(z!="" && z!="-" && z!="0") {
  if(z<nyito) alert('A záróállás nem lehet kisebb mint '+nyito+'.'); 
 } 
}

var modositva = 0;
function szamol(indulo,current,ceg,ora,ho,kozmu,orakSzama) {
	fogy = 0;
	haviOssz = 0;
	eviOssz = 0;
	ho = ho*1;
	if(current=="-") current=0;	
	if(indulo=="") indulo = $("#allas_c"+ceg+"_h"+(ho-1)+"_o"+ora).val();
	indulo = indulo*1;
	evesOraFogy=0;

	switch(kozmu)
	{
	 case "1":
		mertekegyseg = "<font class=\"kwh\">kWh</font>";
		break;
	 case "2":
		mertekegyseg = "<font class=\"kwh\">m<sup>3</sup></font>";
		break;
	 default:
		mertekegyseg = "<font class=\"kwh\">m<sup>3</sup></font>";
	}
	
	fogy = current-indulo; 
	
  	if(current>=indulo) { 	 
	 beirni = fogy+" "+mertekegyseg;
	} 
	else {	 
	 fogy = 0; 
	 beirni = "<font color='red'>NaN</font>"; 
	}
	

	 
	$("#havi_fogy_c"+ceg+'_o'+ora+'_h'+ho).html(addCommas(beirni)); // óra aktuális fogyasztás
	$("#rejtett_havi_fogy_c"+ceg+'_o'+ora+'_h'+ho).val(fogy);
	
	// Havi összfogyasztás
	for(i=1;i<=orakSzama;i++) {
	 aktualisOraFogy = ($("#rejtett_havi_fogy_c"+ceg+'_o'+i+'_h'+ho).val())*1; 
	 haviOssz = haviOssz+aktualisOraFogy;
	}
	
	$("#fogy_havi_c"+ceg+'_h'+ho).html(addCommas(haviOssz)+" "+mertekegyseg);	// cég havi fogyasztása
	egysegar = ($("#egysegar_c"+ceg+'_h'+ho).val())*1;
	forint = haviOssz*egysegar;
	$("#ertek_havi_c"+ceg+'_h'+ho).html(addCommas(forint)+" Ft");
	
	
    // Éves órafogyasztás
	for(i=1;i<=12;i++) {
	 haviOraFogy = ($("#rejtett_havi_fogy_c"+ceg+'_o'+ora+'_h'+i).val())*1; 
	 evesOraFogy = evesOraFogy+haviOraFogy;
	}	
	$("#eviOsszfogy_c"+ceg+"_o"+ora).html(addCommas(evesOraFogy)+" "+mertekegyseg);
	$("#rejtett_eviOsszfogy_c"+ceg+"_o"+ora).val(evesOraFogy);
	
	// Éves összfogyasztás
	for(i=1; i<=orakSzama; i++) {
	 evesOraFogyasztas = ($("#rejtett_eviOsszfogy_c"+ceg+"_o"+i).val())*1
	 eviOssz = eviOssz+evesOraFogyasztas;
	}
	$("#eviFogy_c"+ceg).html(addCommas(eviOssz)+" "+mertekegyseg);
	ertekEves=eviOssz*egysegar;
	$("#ertekEves_c"+ceg).html(addCommas(ertekEves)+" Ft");
	
	modositva=1;
}

function addCommas(nStr)
{
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
}	
</script> 
<form action="" id="target" name="formVillany" method="post">
  <div class="cegekMenu">
	 <?php cegvalszto(); ?>
	
	 
	 <div class="szuro" style="background-image:url('images/save-icon.png');background-size:auto 55%;background-position:left center;margin-right:0px;margin-top:0px;margin-left:363px;max-height:34px;position:absolute;top:176px;left:50%;">
		<input class="parameter" type="submit" value="Mentés" name="submitFogyasztas" style="width:83px;border:none;background-color: #fff;padding: 6px 10px 6px 10px;margin:0px 0px 0px 22px;font-size: 18px !important;font-weight: bold;color: #232323;font-family: Arial;cursor:pointer;line-height:20px;max-height:34px;">
	 </div>
	 
  </div>
  
  <div class="fogyasztasTartalom">
  
		 
			<?php getCegtabla(); ?>
		 	
		
 		
	<div id="ceg0" class="cegTabla">			  
			 		  
			  <table id="selectModTable"  class="noprint" cellpadding="0" cellspacing="none" border="0">
			  <tr><td valign="top"><div id="modValaszto">Havi összesítõ</div></td></tr>
			  <tr>
			  <td>
			  <div id="modok">
			   <div id="haviOssz" class="first aktiv">Havi összesítõ</div>
			   <div id="eviOssz" class="last">Éves összesítõ</div>
			  </div></td></tr>
			  </table>
			  
			  <table id="selectTable" class="noprint" cellpadding="0" cellspacing="none" border="0">
			  <tr><td valign="top"><div id="hoValaszto"><?php getHoNev(); ?></div></td></tr>
			  <tr>
			  <td>
			  <div id="honapok"><?php honapok(); ?></div></td></tr></table>
			 	
		
		<div id="haviOsszes"><?php getOsszesenHavi(); ?></div>
		<div id="evesOsszes" style="display:none">
			<font id="cimNyomtatni"><b><? echo $_SESSION['ev'] ?> évi <? echo $_SESSION['kozmu_nev']; ?> fogyasztás összesítõ</b></font><br><br>
			<?php getOsszesen(); ?>
		</div>
	</div>		
  </div>
</form>  