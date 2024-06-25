<?php
header("Content-Type: text/plan; charset=iso-8859-2");
session_start();
include('kapcsolat.php');

$q = mysql_query("SELECT * FROM kozmu ORDER BY sorrend");
$i=0;
while($m=mysql_fetch_array($q)) {
$elemek[$i] = $m['kozmu'];
$x[$i] = $m['id_kozmu'];
$i++;
}

$update=0;
$j=1; 
foreach($_POST['elem'] as $sor){	
	$sort = "UPDATE kozmu SET sorrend=".$j." WHERE id_kozmu='".$x[$sor-1]."'";	
	if(!mysql_query($sort)) echo mysql_error(); else $update=1;	
	$j++;
}

function getAr($kozmu,$ev,$ho) {
	$sql = mysql_query("SELECT * FROM arak WHERE kozmu='$kozmu' AND ev='$ev' AND ho='$ho'");
	$ar = mysql_fetch_array($sql);
	if($ar['egysegar']=="") return('<a href="#arak'.$kozmu.'" rel="prettyPhoto">nincs adat</a>'); else return('<a href="#arak'.$kozmu.'" rel="prettyPhoto">'.$ar['egysegar'].',-Ft</a>');
}

//if($update==1) echo '<p><b>Változtatások elmentve!</b></p>';

$q=mysql_query("SELECT * FROM kozmu ORDER BY sorrend");
$i=1;
while($t=mysql_fetch_array($q)) {	 
	 
	 if($i%2==0) $row = "alt-row"; else $row="";
	 
	 echo ' <div id="elem_'.$i.'"><table width="100%" cellpadding="0" cellspacing="0" stlye="margin:0px auto !important;">';
	 echo '<tr class="'.$row.'">
	 <td width="274px">'.$t['kozmu'].'</td>
	 <td width="336px">'.$t['mertekegyseg'].'</td>
	 <td>'.getAr($t['id_kozmu'],$_SESSION['ev'],date('m')).'</td>	 
	 <td class="actions"><img src="images/edit.png" class="icon" style="visibility:hidden;"></td>
	 <td class="actions"><img src="images/delete.png" class="icon" style="visibility:hidden;"></td></tr>';	 	 
	 echo '</table></div>'."\r\n";
	 
$i++;
}

mysql_close($kapcsolat);
?>