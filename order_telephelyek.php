<?php
header("Content-Type: text/plan; charset=iso-8859-2");
include('kapcsolat.php');
?>
<!-- Drag & Drop-hoz -->
				<script src="jquery.min.js" type="text/javascript"></script>
				<script src="jquery-ui.min.js" type="text/javascript"></script>	
				<script type="text/javascript">
				$(document).ready(function(){
				
				 //$('tbody tr:even').addClass("alt-row");	
				
				 $(function() {
					$("#list").sortable({ opacity: 0.5, cursor: 'n-resize', update: function() {
					 
					 var order = $(this).sortable("serialize");
					 /*$.post("update_sorrend_cegek.php", order, function(theResponse){
					  $("#list").html(theResponse);					 
					 });*/
				   }
				  });
				 });			 
				
				
				$("#saveListBtn").click(function(){
				 var order = $("#list").sortable("serialize");
				 $.post("update_sorrend_telephelyek.php", order, function(theResponse){					 
					 $("#gombok").animate({marginTop:'10px'},"slow",function(){ 
					  $("#list").html(theResponse);
					  window.location.href='?p=telephelyek'; 
					 });				 
					 
				 });	
				});
				 
				});
				</script>
				<!-- Drag & Drop-hoz eddig -->

<style>
#list {
	margin: 0px auto 10px auto;
	width: 100%;	
}

#list div {
	cursor		: n-resize;
	margin		: 0px;	
}

.lista table {
	margin		: 0px auto !important;		
}
</style>

<table class="cegSzerkTabla" style="margin-top:10px !important;">
	<tr class="alt-row" id="fejlec">
	 <td><b>Terület</b></td>
	 <td colspan="2">&nbsp;</td>
	</tr>	
</table>
<div id="list">
<?php
$q = mysql_query("SELECT * FROM telephely ORDER BY sorrend");
$i=1;
while($t=mysql_fetch_array($q)) { 
	 
	 if($i%2==0) $row = "alt-row"; else $row="";
	 
	 echo ' <div id="elem_'.$i.'"><table width="100%" cellpadding="0" cellspacing="0" stlye="margin:0px auto !important;">';
	 echo '<tr class="'.$row.'">
	 <td>'.$t['cim'].'</td>';
	 
	 echo '<td class="actions"><img src="images/edit.png" class="icon"></td>
	 <td class="actions"><img src="images/delete.png" class="icon"></td>
	 </tr>';
	 echo '</table></div>'."\r\n";
	 
$i++;
}
mysql_close($kapcsolat);
?>	
</div>