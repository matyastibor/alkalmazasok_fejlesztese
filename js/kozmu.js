$(document).ready(function(){
	
	$("body").click(function(){
		$('.ertek').hide();
		$("#honapok, #modok").hide();		
	});	
	
	$(".ertek a").click(function(e){
		   e.stopPropagation();
		   e.preventDefault();		   
		   var text = $(this).text();
		   //alert(text);
		   var szulo = $(this).parents('div.szuro');
		   var parameter = szulo.children('div.parameter');
		   parameter.html(text);
		   
		   var szulo2 = $(this).parents('div.ertek');
		   var ertekek = szulo2.children('a');
		   ertekek.removeClass("aktiv");
		   $(this).addClass('aktiv');
		   
		   szulo2.slideUp('fast');
		   
		// Postolás
		var szuro = $(this).attr("rel");
		var szuroErtek = $(this).attr("href");

		$.post("fogyasztas.php?"+szuro+"="+szuroErtek,        
        {
			 text: $(this).text()			 
		},
		function(result){			
			$("#fogyasztasWrapper").html(result);			
		});		   
	});	

	$(".szuro").click(function(e){
		   e.stopPropagation();	
		   //$('.ertek').hide(function(){});
		   $(this).children('div.ertek').slideToggle('fast');
	});	
	
});