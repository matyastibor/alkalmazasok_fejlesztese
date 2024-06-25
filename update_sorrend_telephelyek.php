<?php
header("Content-Type: text/plan; charset=iso-8859-2");
include('kapcsolat.php');

$q = mysql_query("SELECT * FROM telephely ORDER BY sorrend");
$i=0;
while($m=mysql_fetch_array($q)) {
$elemek[$i] = $m['cim'];
$x[$i] = $m['id_telephely'];
$i++;
}

$update=0;
$j=1; 
foreach($_POST['elem'] as $sor){	
	$sort = "UPDATE telephely SET sorrend=".$j." WHERE id_telephely='".$x[$sor-1]."'";	
	if(!mysql_query($sort)) echo mysql_error(); else $update=1;	
	$j++;
}

//if($update==1) echo '<p><b>Változtatások elmentve!</b></p>';
$sql = mysql_query("SELECT * FROM telephely ORDER BY sorrend");
$i=1;

while($t = mysql_fetch_array($sql)) {
	
	 if($i%2==0) $row = "alt-row"; else $row="";
	 
	 echo ' <div id="elem_'.$i.'"><table width="100%" cellpadding="0" cellspacing="0" stlye="margin:0px auto !important;">';
	 echo '<tr class="'.$row.'">
	 <td>'.$t['cim'].'</td>';
	 
	 echo '<td class="actions"><a href="JavaScript:void()"><img src="images/edit.png" class="icon" title="Szerkesztés"></a></td>
	 <td class="actions"><a href="JavaScript:void()"><img src="images/delete.png" class="icon" title="Törlés"></a></td>
	 </tr>';
	 echo '</table></div>'."\r\n";
	 
$i++; 
}

mysql_close($kapcsolat);
?>