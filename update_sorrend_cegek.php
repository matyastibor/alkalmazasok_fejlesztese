<?php
header("Content-Type: text/plan; charset=iso-8859-2");
include('kapcsolat.php');

$ho = array('', 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');

$q = mysql_query("SELECT * FROM ceg WHERE cegnev NOT LIKE 'Összesen:' ORDER BY telephely,sorrend");
$i=0;
while($m=mysql_fetch_array($q)) {
$elemek[$i] = $m['cegnev'];
$x[$i] = $m['id_ceg'];
$i++;
}

$update=0;
$j=1; 
foreach($_POST['elem'] as $sor){	
	$sort = "UPDATE ceg SET sorrend=".$j." WHERE id_ceg='".$x[$sor-1]."'";	
	if(!mysql_query($sort)) echo mysql_error(); else $update=1;	
	$j++;
}

//if($update==1) echo '<p><b>Változtatások elmentve!</b></p>';

$q=mysql_query("SELECT * FROM ceg WHERE cegnev NOT LIKE 'Összesen' ORDER BY telephely,sorrend");
$i=1;
while($m=mysql_fetch_array($q)) {
	$t2 = mysql_fetch_array(mysql_query("SELECT * FROM telephely WHERE id_telephely = '".$m['telephely']."'"));	
	 if($t['aktiv']=="nem") $aktiv= '<font color="red">'.$m['aktiv'].'</font>';
	 else $aktiv = $m['aktiv'];
	 
	 if($i%2==0) $row = "alt-row"; else $row="";
	 
	 echo ' <div id="elem_'.$i.'"><table width="100%" cellpadding="0" cellspacing="0" stlye="margin:0px auto !important;"><tr>';
	 echo '<tr class="'.$row.'">
	 <td width="279px"><a href="Javascript:void()"><b>'.$m['cegnev'].'</b></a></td>
	 <td width="220px">'.$t2['cim'].'</td>';	 
	 echo '<td width="127px">';
	  if($m['nyito_ev']!="0") echo $m['nyito_ev']." ".$ho[$m['nyito_ho']];
	 echo '</td>';
	 echo '<td width="127px">';
	  if($m['zaro_ev']!="0") echo $m['zaro_ev']." ".$ho[$m['zaro_ho']];
	 echo '</td>';
	 echo '<td>'.$aktiv.'</td>
	 </tr>';
	 echo '</tr></table></div>'."\r\n";
	 
$i++;
}

mysql_close($kapcsolat);
?>