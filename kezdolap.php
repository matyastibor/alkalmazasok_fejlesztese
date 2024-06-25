<?php loginControll(); ?>

<script>
$( "body" ).on( "keydown", function( event ) {
			
		 if(event.which==39) { // JOBBRA nyíl
		 
			$(".cegekMenu a").removeClass("aktiv");
			$(".cegTabla").hide();
			if(mutat<maxCeg) mutat++;			
			$("#mutato").html('<div class="szamlalo">'+mutat+'/'+maxCeg+'</div>');
			ceg = $(".cegekMenu").children("a");
			$(ceg).each(function() {			 
			 if($(this).attr("id")==mutat) {				 
				 $(this).addClass("aktiv");				 
				 var cegIndex = $(this).attr("href"); 
				 $("#ceg"+cegIndex).show();				 
			 } 
			}); 
			
		 } // JOBBRA nyíl eddig
		 

		 if(event.which==37) { // BALRA nyíl innen

			$(".cegekMenu a").removeClass("aktiv");
			$(".cegTabla").hide();
			if(mutat>1) mutat--;
			$("#mutato").html('<div class="szamlalo">'+mutat+'/'+maxCeg+'</div>');
			ceg = $(".cegekMenu").children("a");
			$(ceg).each(function() {			 
			 if($(this).attr("id")==mutat) {				 
				 $(this).addClass("aktiv");				 
				 var cegIndex = $(this).attr("href"); 
				 $("#ceg"+cegIndex).show();				 
			 } 
			}); 		 

		 } // BALRA nyíl eddig

});

$(document).ready(function(){
	$.ajax({
		url:"fogyasztas.php",
		contentType: 'application/xml;charset=iso-8859-2',
		success: function(result){
			$("#fogyasztasWrapper").html(result);			
	    }
	 });
}); 
</script>

<h1 class="noPrint" style="margin-bottom:14px !important;">Fogyasztási adatok</h1>

<!-- Szûrõk -->
<div class="szurok">

 <div class="szuro">
  <div class="parameter"><?php echo $_SESSION['telephely_cim']; ?></div>
  <div class="ertek"><?php telephelyvalszto(); ?></div>
 </div>
 
 <div class="szuro">
  <div class="parameter"><?php echo $_SESSION['kozmu_nev']; ?></div>
  <div class="ertek"><?php kozmuvalszto(); ?></div>
 </div>
 
 <div class="szuro">
  <div class="parameter rovid"><?php echo $_SESSION['ev']; ?></div>
  <div class="ertek rovid">
	<a href="2014" rel="ev" <?php if($_SESSION['ev']==2014) echo 'class="aktiv"' ?>>2014</a>
	<a href="2015" rel="ev" <?php if($_SESSION['ev']==2015) echo 'class="aktiv"' ?>>2015</a>
	<a href="2016" rel="ev" <?php if($_SESSION['ev']==2016) echo 'class="aktiv"' ?>>2016</a>
	<a href="2017" rel="ev" <?php if($_SESSION['ev']==2017) echo 'class="aktiv"' ?>>2017</a>
	<a href="2018" rel="ev" <?php if($_SESSION['ev']==2018) echo 'class="aktiv"' ?>>2018</a>
	<a href="2024" rel="ev" <?php if($_SESSION['ev']==2024) echo 'class="aktiv"' ?>>2024</a>
  </div>
 </div>

 <div class="szuro" id="mutato"><div class="szamlalo">0/0</div></div>
 
 <div class="szuro" style="background-image:url('images/print.gif');background-size:auto 55%;background-position:left center;" onClick="$('.cegTabla').show();$('.cegTabla').addClass('page');$('#ceg0').removeClass('page');$('.cegTabla').addClass('toPrint');myFunction();$('.cegTabla').hide();$('#ceg'+cegIndex).show();">
  <div class="parameter" style="width:90px;">Nyomtatás</div>
 </div>
 

 <div class="szuro" style="background-image:url('images/save-icon.png');background-size:auto 55%;background-position:left center;margin-right:0px;">
  <div class="parameter" style="width:63px;" onClick="document.formVillany.submit();">Mentés</div>
 </div>

 
</div>	
	

<div class="fogyasztas" id="fogyasztasWrapper"></div>