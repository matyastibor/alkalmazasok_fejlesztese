<?php
function list_fogy() {
include("kapcsolat.php");
$sql = mysql_query("SELECT * FROM fogyasztas ORDER BY id_fogy");
while($f = mysql_fetch_array($sql)) {
	$sql2 = mysql_query("SELECT * FROM fogyasztas WHERE id_mero='".$f['id_mero']."' AND ev='".$f['ev']."' AND ho='".$f['ho']."' ORDER BY id_mero ASC");
	$n = mysql_num_rows($sql2);
	if($n>1) {
	 while($f2=mysql_fetch_array($sql2)) {
	  echo "<p>".$f2['id_mero'].": ".$f2['ev']."-".$f2['ho'].". ".$f2['nyito']."-".$f2['zaro']."</p>";
	 }
	}
}
mysql_close($kapcsolat);
}

list_fogy();
?>