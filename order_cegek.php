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
				 
				});
				
				$("#saveListBtn").click(function(){
				 var order = $("#list").sortable("serialize");
				 $.post("update_sorrend_cegek.php", order, function(theResponse){					 
					 $("#gombok").animate({marginTop:'10px'},"slow",function(){ 
					  $("#list").html(theResponse);
					  window.location.href='?p=cegek'; 
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
	 <td width="279px"><b>Ügyfél</b></td>
	 <td width="220px"><b>Telephely, terület</b></td>
	 <td width="127px"><b>Megnyitva</b></td>
	 <td width="127px"><b>Lezárva</b></td>
	 <td><b>Aktív</b></td>
	</tr>	
</table>
<div id="list">
<?php
$ho = array('', 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');

$q=mysql_query("SELECT * FROM ceg WHERE cegnev NOT LIKE 'Összesen:' ORDER BY telephely,sorrend");
$i=1;
$style="";
while($m=mysql_fetch_array($q)) {
	 $t2 = mysql_fetch_array(mysql_query("SELECT * FROM telephely WHERE id_telephely = '".$m['telephely']."'"));	
	 if($t['aktiv']=="nem") $aktiv= '<font color="red">'.$m['aktiv'].'</font>';
	 else $aktiv = $m['aktiv'];
	 
	 if($i%2==0) $row = "alt-row"; else $row="";
	 
	 echo ' <div id="elem_'.$i.'"><table width="100%" cellpadding="0" cellspacing="0" stlye="margin:0px auto !important;">';
	 echo '<tr class="'.$row.'">
	 <td width="279px"><a href="Javascript:void()" style="cursor:n-resize;"><b>'.$m['cegnev'].'</b></a></td>
	 <td width="220px">'.$t2['cim'].'</td>';	 
	 echo '<td width="127px">';
	  if($m['nyito_ev']!="0") echo $m['nyito_ev']." ".$ho[$m['nyito_ho']];
	 echo '</td>';
	 echo '<td width="127px">';
	  if($m['zaro_ev']!="0") echo $m['zaro_ev']." ".$ho[$m['zaro_ho']];
	 echo '</td>';
	 echo '<td>'.$aktiv.'</td>
	 </tr>';
	 echo '</table></div>'."\r\n";
	 
$i++;
}
mysql_close($kapcsolat);
?>	
</div>