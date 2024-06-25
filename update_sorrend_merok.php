<?php
header("Content-Type: text/plan; charset=iso-8859-2");
include('kapcsolat.php');

$ho = array('', 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');

$q = mysql_query("SELECT * FROM mero ORDER BY sorrend");
$i=0;
while($m=mysql_fetch_array($q)) {
$elemek[$i] = $m['meroazon'];
$x[$i] = $m['id_mero'];
$i++;
}

$update=0;
$j=1; 
foreach($_POST['elem'] as $sor){	
	$sort = "UPDATE mero SET sorrend=".$j." WHERE id_mero='".$x[$sor-1]."'";	
	if(!mysql_query($sort)) echo mysql_error(); else $update=1;	
	$j++;
}

//if($update==1) echo '<p><b>Változtatások elmentve!</b></p>';

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