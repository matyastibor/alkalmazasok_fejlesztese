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
				 $.post("update_sorrend_merok.php", order, function(theResponse){					 
					 $("#gombok").animate({marginTop:'10px'},"slow",function(){ 
					  $("#list").html(theResponse);
					  window.location.href='?p=merok'; 
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
	<!--
	<tr class="alt-row">
	 <td width="279px"><b>�gyf�l</b></td>
	 <td width="220px"><b>Telephely, ter�let</b></td>
	 <td width="127px"><b>Megnyitva</b></td>
	 <td width="127px"><b>Lez�rva</b></td>
	 <td><b>Akt�v</b></td>
	</tr>
	-->
	<tr class="alt-row" id="fejlec">
	 <td width="140px"><b>M�r�</b></td>
	 <td width="155px"><b>K�zm�</b></td>
	 <td width="217px"><b>C�g</b></td>
	 <td width="123px"><b>Megnyitva</b></td>
	 <td width="123px"><b>Lez�rva</b></td>
	 <td><b>Akt�v</b></td>
	</tr>
</table>

<div id="list">

<?php
$ho = array('', 'janu�r', 'febru�r', 'm�rcius', '�prilis', 'm�jus', 'j�nius', 'j�lius', 'augusztus', 'szeptember', 'okt�ber', 'november', 'december');

$sql = mysql_query("SELECT * FROM mero ORDER BY sorrend");
$i=1;
$style="";

while($t = mysql_fetch_array($sql)) {
	
	 if($i%2==0) $row = "alt-row"; else $row="";
	 
	 $t2 = mysql_fetch_array(mysql_query("SELECT * FROM kozmu WHERE id_kozmu = '".$t['kozmu']."'"));
	 $t3 = mysql_fetch_array(mysql_query("SELECT * FROM ceg WHERE id_ceg = '".$t['id_ceg']."'"));
	 if($t['aktiv']=="nem") $aktiv= '<font color="red">'.$t['aktiv'].'</font>';
	 else $aktiv = $t['aktiv'];
	 echo '<div id="elem_'.$i.'"><table width="100%" cellpadding="0" cellspacing="0" stlye="margin:0px auto !important;">';	 
	 echo '<tr class="'.$row.'">
	 <td width="140px"><a href="JavaScript:void()"><b>'.$t['meroazon'].'</b></a></td>
	 <td width="155px">'.$t2['kozmu'].'</td>
	 <td width="217px">'.$t3['cegnev'].'</td>';	 
	 echo '<td width="123px">';
	  if($t['nyito_ev']!="0") echo $t['nyito_ev']." ".$ho[$t['nyito_ho']];
	 echo '</td>';
	 echo '<td width="123px">';
	  if($t['zaro_ev']!="0") echo $t['zaro_ev']." ".$ho[$t['zaro_ho']];
	 echo '</td>';
	 echo '<td>'.$aktiv.'</td>
	 </tr>';
	 echo '</table>';
	 echo '</div>';

$i++;	 
}

mysql_close($kapcsolat);
?>	
</div>