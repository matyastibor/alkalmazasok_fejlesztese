<div id="gombKeret" style="overflow:hidden;float:right;height:54px;width:400px;margin-top:-10px;">
<div id="gombok" style="float:right;margin-top:10px;">

<div class="szuro noprint" id="ujMero" style="background-image:url(images/next-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:0px;margin-right:0px;margin-bottom:10px;width:202px;">
     <div class="parameter" style="width:160px;text-indent:4px;"><a href="#uj" rel="prettyPhoto" style="color:#232323">M�r� hozz�ad�sa</a></div>
</div>

<div class="szuro noprint" id="sorrendGomb" style="background-image:url(images/sort.png);background-size:auto 60%;background-position:1px center;float:right;margin-top:0px;margin-right:10px;margin-bottom:10px;width:132px;">
     <div class="parameter" id="sorrend" style="width:90px;text-indent:4px;">Sorrend</div>
</div>

<input type="button" value="" id="saveListBtn" class="saveBtnPretty" style="margin-right:0px;margin-left:10px;">

<div class="szuro noprint" id="visszaCegekBtn" style="background-image:url(images/back-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:0px;margin-right:0px;margin-bottom:10px;width:132px;">
     <div class="parameter" id="visszaCegek" style="width:90px;text-indent:4px;">Vissza</div>
</div>

</div>
</div>

<script>
$("document").ready(function(){
	$("#sorrend").click(function(){
		//$("#ujMero").hide();
		//$("#sorrendGomb").hide();
		//$("#visszaCegekBtn, #saveListBtn").show();
		$("#gombok").animate({marginTop:'-34px'},"slow");
		
		$.ajax({
		url:"order_merok.php",
		contentType: 'application/xml;charset=ISO-8859-2',
		success: function(result){
			$("#orderWrapper").html(result);			
	    }
	   });
	});
	
	
	$("#visszaCegek").click(function(){
		$("#gombok").animate({marginTop:'10px'},"slow",function(){
		 window.location.href='?p=merok';
		});		
	});
});
</script>

<h1>M�r�k kezel�se</h1>
<div class="clear"></div>
<div class="lista" id="orderWrapper">
<?php 
loginControll();
getMerok();
?>
</div>