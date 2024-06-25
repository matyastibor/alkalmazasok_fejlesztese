<?php
/* ##### GLOBÁLISAN HASZNÁLT FÜGGVÉNYEK INNEN ##### */

function loginControll() {
 if($_SESSION['login']!=1) header('Location:login.php');
}

function login() {
	if(isset($_POST['submitLogin'])) {
		$sql = mysql_query("SELECT * FROM user WHERE password='".md5($_POST['password'])."'");
		$n = mysql_num_rows($sql);
		if($n>0) {
			$_SESSION['login']=1;
			header('Location:./?p=kezdolap');
		} else header('Location:login.php?error=bad_pswd');
	}
}

function logout() {
	if(isset($_GET['logout'])) {
		session_destroy();
		header('Location:./');
	}	
}
logout();

function penzFormatum($x) {
	$x = strval($x);
	$y = "";
	$pont = 0; 	
	for($c=strlen($x);$c>=0;$c--){
	if($pont%3==0 && $pont!=0 && $c!=0) {$ar[$c] = ".".$x[$c];} else {$ar[$c] = $x[$c];} 
	$pont++;
	}
	for($c=0;$c<=strlen($x);$c++)  $y .= $ar[$c];
	return($y);
}

function jquery2iso($in)
{
  $CONV = array();
  $CONV['c3']['a1'] = 'á';
  $CONV['c3']['81'] = 'Á';
  $CONV['c3']['a9'] = 'é';
  $CONV['c3']['89'] = 'É';
  $CONV['c3']['ad'] = 'í';
  $CONV['c3']['8d'] = 'Í';
  $CONV['c3']['b6'] = 'ö';
  $CONV['c3']['96'] = 'Ö';
  $CONV['c5']['91'] = 'õ';
  $CONV['c5']['90'] = 'Õ';  
  $CONV['c3']['b3'] = 'ó';
  $CONV['c3']['93'] = 'Ó';
  $CONV['c3']['ba'] = 'ú';
  $CONV['c3']['9a'] = 'Ú';
  $CONV['c3']['bc'] = 'ü';
  $CONV['c3']['9c'] = 'Ü';
  $CONV['c5']['b1'] = 'û';
  $CONV['c5']['b0'] = 'Û';  

  $i=0;
  $out = '';
  while($i<strlen($in))
  {
    if(array_key_exists(bin2hex($in[$i]), $CONV))
    {
      $out .= $CONV[bin2hex($in[$i])][bin2hex($in[$i+1])];
      $i += 2;
    }
    else
    {
      $out .= $in[$i];
      $i += 1;
    }
  }

  return $out;
}

/* ### TARTALOM kezelése ### */
function contentLoading() {
	if(isset($_GET['p'])) {
		$page = $_GET['p'].".php";
		if(file_exists($page)) include($page);
		else include("error_404.php");
	} else include("kezdolap.php");
}

function aktivMenupont($link) {
	$mi = explode('#',$link);
	if($mi[0]==$_GET['p'] || $mi[1]==$_GET['p']) echo 'aktiv';	
	if($_GET['p']=="" && $mi[0]=="kezdolap") echo 'aktiv';
}

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	if(isset($_POST['submitExportDatabase'])) {
		$sql = mysql_query("SELECT * FROM user WHERE password='".md5($_POST['password'])."'");
		$n = mysql_num_rows($sql);
		if($n>0) {
			
//$link = mysql_connect($host,$user,$pass);
	//mysql_select_db($name,$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//save file
	$backupFile = 'save_database/db-backup-'.date('Y-m-d').'_'.date('H-i-s').'.sql';
	$handle = fopen($backupFile,'w+');
	fwrite($handle,$return);
	fclose($handle);
	header('Location:?p=export&file='.$backupFile);			
		
		} else header('Location:login.php?error=bad_pswd');
	}	
}





/* ##### BEÁLLÍTÁSOK RÉSZ FÜGGVÉNYEI INNEN ##### */





/* ### TELEPHELYEK kezelése ### */

function getTelephelyek() {
	echo '<table><tr id="fejlec"><td><b>Terület</b></td><td colspan="2">&nbsp;</td></tr>';
	$sql = mysql_query("SELECT * FROM telephely ORDER BY sorrend");
	$n = mysql_num_rows($sql);
$i=1;	
if($n>0) {
	while($t = mysql_fetch_array($sql)) {
	 if($i%2==0) $row = "alt-row"; else $row="";
	 echo '<tr class="'.$row.'">
	 <td>'.$t['cim'].'</td>
	 <td class="actions"><a href="#editTelephely'.$t['id_telephely'].'" rel="prettyPhoto"><img src="images/edit.png" class="icon" title="Szerkesztés"></a></td>
	 <td class="actions"><a href="#deleteTelephely'.$t['id_telephely'].'" rel="prettyPhoto"><img src="images/delete.png" class="icon" title="Törlés"></a>
	 
	  <!-- ### Telephely szerkesztése ### -->

	  <div id="editTelephely'.$t['id_telephely'].'" style="display:none;height:90px;float:left;">
		<form action="" method="post">
		<h1>Terület, telephely szerkesztése</h1><br>		
		<div align="right"><table class="prettyTable"><tr><td>Terület, telephely megnevezése:&nbsp;</td><td><input type="text" name="telephely" value="'.$t['cim'].'" required class="inputPretty">  <input type="hidden" name="id_telephely" value="'.$t['id_telephely'].'"></td></tr></table></div>
		<input type="submit" name="submitEditTelephely" class="saveBtnPretty" value="">
		</form>
	  </div>
	  
	  <!-- ### Telephely törlése ### -->	  
	  
	  <div id="deleteTelephely'.$t['id_telephely'].'" style="display:none;height:140px;float:left;">
		<form action="" method="post">
		<h1>Terület, telephely törlése</h1>		
		<p>Biztosan törli ezt a szolgáltatási területet: <span><b>'.$t['cim'].'</b></span>?</p>
		<p>Amennyiben a törlés gombra kattint, a telephelyhez kapcsolódó minden adat törlésre kerül.</p>		
		<div align="right"><table class="prettyTable"><tr><td>Jelszó:&nbsp;</td><td><input type="password" name="password" class="inputPretty"> <input type="hidden" name="id_telephely" value="'.$t['id_telephely'].'"> </td></tr></table></div>
		<input type="submit" name="submitDeleteTelephely" class="deleteBtnPretty" value="">
		
		</form>
	  </div>

	<!-- ### Új telephely ûrlap ### -->
		
		<div id="ujTelephely" style="display:none;height:90px;float:left;">
			<form action="" method="post">
			 <h1>Új terület, telephely rögzítése</h1><br>		
			 <div align="right"><table class="prettyTable"><tr><td>Terület, telephely megnevezése:&nbsp;</td><td><input type="text" name="telephely" required class="inputPretty"></td></tr></table></div>
			 <input type="submit" name="submitUjTelephely" class="saveBtnPretty" value="">
			</form>
		</div>	
	  
	 </td></tr>';
	 $i++;
	}	
} else {
	echo '<tr><td colspan="3" align="center">Még nem rögzített szolgáltatási helyszínt.</td></tr>';
}	
	echo '</table>';	
}

function setTelephely() {
	if(isset($_POST['submitUjTelephely'])) {
		mysql_query("INSERT INTO telephely(id_telephely, cim) VALUES(NULL,'".mysql_real_escape_string($_POST['telephely'])."')");
		header("Location:?p=telephelyek");
	}
}
setTelephely(); 

function updateTelephely() {
	if(isset($_POST['submitEditTelephely'])) {
		mysql_query("UPDATE telephely SET cim='".mysql_real_escape_string($_POST['telephely'])."' WHERE id_telephely='".mysql_real_escape_string($_POST['id_telephely'])."'");
		header("Location:?p=telephelyek");
	}
}
updateTelephely();

function deleteTelephely() {
	if(isset($_POST['submitDeleteTelephely'])) {
		$sql = mysql_query("SELECT * FROM user WHERE password='".md5($_POST['password'])."'");
		$n = mysql_num_rows($sql);
		if($n>0) {
			mysql_query("DELETE FROM telephely WHERE id_telephely='".mysql_real_escape_string($_POST['id_telephely'])."'");
			header("Location:?p=telephelyek");
		} else header('Location:login.php?error=bad_pswd');		
	}	
}
deleteTelephely();

function selectTelephely($id_telephely) {
	echo '<select name="telephely" class="inputPretty" style="width:224px;text-indent:1px;">';
	$sql = mysql_query("SELECT * FROM telephely ORDER BY cim");
	while($t = mysql_fetch_array($sql)) {
		if($id_telephely!="" && $id_telephely==$t['id_telephely']) echo '<option value="'.$t['id_telephely'].'" selected="selected">'.$t['cim'].'</option>';
		else echo '<option value="'.$t['id_telephely'].'">'.$t['cim'].'</option>';
	}
	echo '</select>';
}



/* ### KÖZMÛVEK kezelése ### */

function getKozmuvek() {
	echo '<table><tr id="fejlec"><td><b>Szolgáltatás</b></td><td><b>Fogyasztási egység</b></td><td><b>Egységár</b></td><td>&nbsp;</td><td>&nbsp;</td></tr>';
	$sql = mysql_query("SELECT * FROM kozmu ORDER BY sorrend");
	$n = mysql_num_rows($sql);
	
if($n>0) {
	while($t = mysql_fetch_array($sql)) {
	 echo '<tr>
	 <td>'.$t['kozmu'].'</td>
	 <td>'.$t['mertekegyseg'].'</td>
	 <td>'.getAr($t['id_kozmu'],$_SESSION['ev'],date('m')).'</td>	 
	 <td class="actions"><a href="#editKozmu'.$t['id_kozmu'].'" rel="prettyPhoto"><img src="images/edit.png" class="icon" title="Szerkesztés"></a></td>
	 <td class="actions"><a href="#deleteKozmu'.$t['id_kozmu'].'" rel="prettyPhoto"><img src="images/delete.png" class="icon" title="Törlés"></a>
	 
	  <!-- ### Árak szerkesztése ### -->
	  <div id="arak'.$t['id_kozmu'].'" style="display:none;max-width:400px;height:340px;float:left;">';	
	  getArak($t['id_kozmu'],$t['kozmu'],$_SESSION['ev']);
	  echo '</div>
	  
	  
	  <!-- ### Közmû szerkesztése ### -->

	  <div id="editKozmu'.$t['id_kozmu'].'" style="display:none;height:130px;float:left;">
		<form action="" method="post">
		<h1>Szolgáltatás szerkesztése</h1><br>		
		<div align="right">
		<table class="prettyTable">
		  <tr>
			 <td>Megnevezés:&nbsp;</td>
			 <td><input type="text" name="kozmu" value="'.$t['kozmu'].'" class="inputPretty"> <input type="hidden" name="id_kozmu" value="'.$t['id_kozmu'].'"></td> 
		  </tr><tr>
			 <td>Fogyasztási egység:&nbsp;</td>
			 <td><input type="text" name="mertekegyseg" value="'.$t['mertekegyseg'].'" class="inputPretty"></td>
		  </tr></table>		
			 <input type="submit" name="submitEditKozmu" class="saveBtnPretty" value=""></div>
		</form>
	  </div>
	  
	  <!-- ### Közmû törlése ### -->	  
	  
	  <div id="deleteKozmu'.$t['id_kozmu'].'" style="display:none;height:160px;float:left;">
		<form action="" method="post">
		<h1>Szolgáltatás törlése</h1><br>		
		<p>Biztosan törli a következõ szolgáltatást: <span><b>'.$t['kozmu'].'</b></span>?</p>
		<p>Amennyiben törli a szolgáltatást, minden hozzá kapcsolódó további információ nem lesz elérhetõ.</p>
		<div align="right">
		<table class="prettyTable"><tr><td>Jelszó:&nbsp;</td><td><input type="password" name="password" class="inputPretty"> <input type="hidden" name="id_kozmu" value="'.$t['id_kozmu'].'"></td></tr></table></div>
		<input type="submit" name="submitDeleteKozmu" value="" class="deleteBtnPretty">		
		</form>
	  </div>	  
	  
	 </td></tr>';	
	}	
} else {
	echo '<tr><td colspan="5" align="center">Még nem rögzített szolgáltatást.</td></tr>';
}
	echo '</table>';
	echo '		
		<!-- ### Új közmû ûrlap ### -->
		
		<div id="ujKozmu" style="display:none;height:130px;float:left;">
			<form action="" method="post" id="ujKozmuForm">
			 <h1>Új szolgáltatás rögzítése</h1><br>		
			 <div align="right"><table class="prettyTable"><tr>
			  <td>Megnevezés:</td>
			  <td><input type="text" name="kozmu" class="inputPretty"></td>
			 </tr><tr>
			  <td>Mértékegység:</td>
			  <td><input type="text" name="mertekegyseg" class="inputPretty"></td>
			 </tr></table></div>
			 <input type="submit" name="submitUjKozmu" value="" class="saveBtnPretty">
			</form>
		</div>
		
		<!--<a href="#ujKozmu" rel="prettyPhoto"><input type="button" value="+ szolgáltatás hozzáadása"></a>-->
	 ';
		
}

function setKozmu() {
	if(isset($_POST['submitUjKozmu'])) {
		mysql_query("INSERT INTO kozmu(id_kozmu, kozmu, mertekegyseg) VALUES(NULL,'".mysql_real_escape_string($_POST['kozmu'])."','".mysql_real_escape_string($_POST['mertekegyseg'])."')");
		header("Location:?p=kozmuvek");
	}
}
setKozmu(); 

function updateKozmu() {
	if(isset($_POST['submitEditKozmu'])) {
		mysql_query("UPDATE kozmu SET kozmu='".mysql_real_escape_string($_POST['kozmu'])."', mertekegyseg='".mysql_real_escape_string($_POST['mertekegyseg'])."' WHERE id_kozmu='".mysql_real_escape_string($_POST['id_kozmu'])."'");
		header("Location:?p=kozmuvek");
	}
}
updateKozmu();

function deleteKozmu() {	
	if(isset($_POST['submitDeleteKozmu'])) {
		$sql = mysql_query("SELECT * FROM user WHERE password='".md5($_POST['password'])."'");
		$n = mysql_num_rows($sql);
		if($n>0) {
			mysql_query("DELETE FROM kozmu WHERE id_kozmu='".mysql_real_escape_string($_POST['id_kozmu'])."'");
			header("Location:?p=kozmuvek");
		} else header('Location:login.php?error=bad_pswd');		
	}
}
deleteKozmu();


/* ### CÉGEK kezelése  ### */

function getCegek() {
	
	$ho = array('', 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');
	
	echo '<table class="cegSzerkTabla"><tr id="fejlec"><td><b>Ügyfél</b></td><td><b>Telephely, terület</b></td><td><b>Megnyitva</b></td><td><b>Lezárva</b></td><td><b>Aktív</b></td></tr>';
	$sql = mysql_query("SELECT * FROM ceg WHERE cegnev NOT LIKE 'Összesen:' ORDER BY telephely, sorrend");
	$n = mysql_num_rows($sql);
	
if($n>0) {
	
	while($t = mysql_fetch_array($sql)) {
	 $t2 = mysql_fetch_array(mysql_query("SELECT * FROM telephely WHERE id_telephely = '".$t['telephely']."'"));	
	 if($t['aktiv']=="nem") $aktiv= '<font color="red">'.$t['aktiv'].'</font>';
	 else $aktiv = $t['aktiv'];
	 
	 /*
	 // Cég nyitó dátumának meghatározása
	 $sql_nydatum = mysql_query("SELECT * FROM mero WHERE id_ceg='".$t['id_ceg']."' ORDER BY nyito_ev,nyito_ho ASC LIMIT 0,1");
	 $nydatum = mysql_fetch_array($sql_nydatum);
	 
	 // Cég záró dátumának meghatározása
	 $sql_zdatum = mysql_query("SELECT * FROM mero WHERE id_ceg='".$t['id_ceg']."' ORDER BY zaro_ev,zaro_ho DESC LIMIT 0,1");
	 $zdatum = mysql_fetch_array($sql_zdatum);	 
	 */
	 
	 echo '<tr>
	 <td><a href="?p=ceg&id='.$t['id_ceg'].'"><b>'.$t['cegnev'].'</b></a></td>
	 <td>'.$t2['cim'].'</td>';	 
	 echo '<td>';
	  if($t['nyito_ev']!="0") echo $t['nyito_ev']." ".$ho[$t['nyito_ho']];
	 echo '</td>';
	 echo '<td>';
	  if($t['zaro_ev']!="0") echo $t['zaro_ev']." ".$ho[$t['zaro_ho']];
	 echo '</td>';
	 echo '<td>'.$aktiv.'</td>
	 </tr>';
	 
	}
	
} else {
	echo '<tr><td colspan="5" align="center">Még nem rögzített céget.</td></tr>';
}
	echo '<tr>
	 <td colspan="5">
		
		<!-- ### Új cég ûrlap ### -->
		
		<div id="ujCeg" style="display:none;height:210px;float:left;">
			<form action="" method="post">
			 <h1>Új ügyfél adatainak rögzítése</h1><br>		
			 <div align="right">
			  <table class="prettyTable">
			   <tr><td><b>Ügyfél neve:</b></td><td><input type="text" name="cegnev" required class="inputPretty"></td></tr>';
	echo 	  '<tr><td><b>Telephely:</b></td><td><input type="text" value="'.$_SESSION['telephely_cim'].'" readonly="readonly" class="inputPretty"> <input type="hidden" name="telephely" value="'.$_SESSION['telephely'].'"></td></tr>';
	echo 	  '<tr>
				<td><b>Sorrend:</b></td>
				<td><div style="height:32px;width:222px;overflow:hidden;float:right;border:1px solid #ababab;margin			: 2px 0px 3px 0px;"><select name="sorrend" class="inputPretty" style="float:left;border:none;width:254px;text-indent:1px;margin:0px;">';
				cegSorrendSelect();
	echo 	  '</select></div></td></tr>'; 	
	echo 	  '<tr><td><b>Listában megjelenik:</b></td><td>
			   <select name="aktiv" class="inputPretty" style="width:224px;text-indent:1px;">
			    <option value="igen">Igen</option>
				<option value="nem">Nem</option>
			   </select>
			   </td></tr>';	
	echo 	  '</table>
				</div>		
			    <input type="submit" name="submitUjCeg" value="" class="saveBtnPretty">
			</form>
		</div>
		
		<!-- <a href="#ujCeg" rel="prettyPhoto"><input type="button" value="+ ügyfél hozzáadása"></a> -->
	 </td>
	</tr>';
	echo '</table>';	
}

function setCeg() {
	if(isset($_POST['submitUjCeg'])) {
	
	// Sorrend elõkészírése	
	 $sorrend = $_POST['sorrend'];
	 $sql = mysql_query("SELECT * FROM ceg WHERE telephely='".mysql_real_escape_string($_POST['telephely'])."' ORDER BY sorrend");
	 while($c = mysql_fetch_array($sql)) {
	  if($c['sorrend']>=$sorrend) mysql_query("UPDATE ceg SET sorrend='".($c['sorrend']+1)."' WHERE id_ceg='".$c['id_ceg']."'"); 
	 }		
		mysql_query("INSERT INTO ceg(id_ceg, cegnev, telephely, sorrend, aktiv) VALUES(NULL,'".mysql_real_escape_string($_POST['cegnev'])."','".mysql_real_escape_string($_POST['telephely'])."','".mysql_real_escape_string($_POST['sorrend'])."','".mysql_real_escape_string($_POST['aktiv'])."')");
		header("Location:?p=cegek");
	}
}
setCeg(); 

function updateCeg() {
	if(isset($_POST['submitEditCeg'])) {		
		if($_POST['aktiv']=="nem") { // Ha le van zárva a cég
		$sql_m = mysql_query("SELECT * FROM mero WHERE id_ceg='".$_POST['id_ceg']."'");
		$max_fogy_id = 0;
		while($m=mysql_fetch_array($sql_m)) {
		 $sql_f = "SELECT * FROM fogyasztas WHERE id_mero='".mysql_real_escape_string($m['id_mero'])."' ORDER BY id_fogy DESC LIMIT 0,1";
		 $q = mysql_query($sql_f);
		 $f = mysql_fetch_array($q);
		 
		 if($f['id_fogy']>$max_fogy_id) $max_fogy_id = $f['id_fogy'];
		 
		 if($f['zaro']=="0") {
			mysql_query("UPDATE fogyasztas SET zaro='".$f['nyito']."' WHERE id_fogy='".$f['id_fogy']."'");
			mysql_query("UPDATE mero SET zaro_ev='".$f['ev']."', zaro_ho='".$f['ho']."', zaro_allas='".$f['nyito']."' WHERE id_mero='".$m['id_mero']."'");
		  } else {
			mysql_query("UPDATE mero SET zaro_ev='".$f['ev']."', zaro_ho='".$f['ho']."', zaro_allas='".$f['zaro']."' WHERE id_mero='".$m['id_mero']."'");
		  }
		  
		 }

		 $sql_f2 = mysql_query("SELECT * FROM mero WHERE id_ceg='".mysql_real_escape_string($_POST['id_ceg'])."' ORDER BY zaro_ev,zaro_ho DESC LIMIT 0,1");
		 $maxfogy = mysql_fetch_array($sql_f2);
		 
		 
		 mysql_query("UPDATE ceg SET cegnev='".mysql_real_escape_string($_POST['cegnev'])."', telephely='".mysql_real_escape_string($_POST['telephely'])."', aktiv='".mysql_real_escape_string($_POST['aktiv'])."', nyito_ev='".mysql_real_escape_string($_POST['nyito_ev'])."', nyito_ho='".mysql_real_escape_string($_POST['nyito_ho'])."', zaro_ev='".$maxfogy['zaro_ev']."', zaro_ho='".$maxfogy['zaro_ho']."' WHERE id_ceg='".mysql_real_escape_string($_POST['id_ceg'])."'");
		 //echo '<script>alert("Lezárva");</script>';
		 
		} else { // Ha nincs lezárva a cég
		
		 mysql_query("UPDATE ceg SET cegnev='".mysql_real_escape_string($_POST['cegnev'])."', telephely='".mysql_real_escape_string($_POST['telephely'])."', aktiv='".mysql_real_escape_string($_POST['aktiv'])."', nyito_ev='".mysql_real_escape_string($_POST['nyito_ev'])."', nyito_ho='".mysql_real_escape_string($_POST['nyito_ho'])."', zaro_ev='0', zaro_ho='0' WHERE id_ceg='".mysql_real_escape_string($_POST['id_ceg'])."'");	
		 //echo '<script>alert("Nincs még lezárva");</script>';
		}
		//header("Location:?p=ceg&id=".$_POST['id_ceg']);
	}
}
updateCeg();

function deleteCeg() {
	if(isset($_POST['submitDeleteCeg'])) {
		$sql = mysql_query("SELECT * FROM user WHERE password='".md5($_POST['password'])."'");
		$n = mysql_num_rows($sql);
		if($n>0) {
			mysql_query("DELETE FROM ceg WHERE id_ceg='".mysql_real_escape_string($_POST['id_ceg'])."'");
			header("Location:?p=cegek");
		} else header('Location:login.php?error=bad_pswd');		
	}
}
deleteCeg();

function selectCeg() {
	echo '<select name="ceg">';
	$sql = mysql_query("SELECT * FROM ceg WHERE telephely='".$_SESSION['telephely']."' ORDER BY sorrend");
	while($t = mysql_fetch_array($sql)) {
		echo '<option value="'.$t['id_ceg'].'">'.$t['cegnev'].'</option>';
	}
	echo '</select>';
}

function cegSorrendSelect() {
     $sql = mysql_query("SELECT * FROM ceg WHERE telephely='".$_SESSION['telephely']."' ORDER BY sorrend");
	 while($c = mysql_fetch_array($sql)) {
	  if($c['cegnev']!="Fõmérõ") { 
	  if($c['cegnev']=="Összesen:")  echo '<option value="'.$c['sorrend'].'" style="font-weight:bold">Utolsó pozíció</option>'."/r/n";  
	  else	echo '<option value="'.$c['sorrend'].'">'.$c['cegnev'].'</option>'."/r/n";  
	  } else echo '<option value="'.($c['sorrend']+1).'" style="font-weight:bold">Elsõ pozíció</option>'."/r/n";
	 }
}


/* ### MÉRÕK kezelése ### */

function getMerok() {
	
	$ev = date('Y');
	$ev++;
	
	$ho = array('', 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');
	
	echo '<table class="cegSzerkTabla"><tr id="fejlec"><td><b>Mérõ</b></td><td><b>Közmû</b></td><td><b>Cég</b></td><td><b>Megnyitva</b></td><td><b>Lezárva</b></td><td><b>Aktív</b></td></tr>';
	$sql = mysql_query("SELECT * FROM mero ORDER BY sorrend");
	$n = mysql_num_rows($sql);
	
if($n>0) {
	while($t = mysql_fetch_array($sql)) {
	 $t2 = mysql_fetch_array(mysql_query("SELECT * FROM kozmu WHERE id_kozmu = '".$t['kozmu']."'"));
	 $t3 = mysql_fetch_array(mysql_query("SELECT * FROM ceg WHERE id_ceg = '".$t['id_ceg']."'"));
	 if($t['aktiv']=="nem") $aktiv= '<font color="red">'.$t['aktiv'].'</font>';
	 else $aktiv = $t['aktiv'];
	 echo '<tr>
	 <td><a href="?p=mero&id='.$t['id_mero'].'"><b>'.$t['meroazon'].'</b></a></td>
	 <td>'.$t2['kozmu'].'</td>
	 <td>'.$t3['cegnev'].'</td>';	 
	 echo '<td>';
	  if($t['nyito_ev']!="0") echo $t['nyito_ev']." ".$ho[$t['nyito_ho']];
	 echo '</td>';
	 echo '<td>';
	  if($t['zaro_ev']!="0") echo $t['zaro_ev']." ".$ho[$t['zaro_ho']];
	 echo '</td>';
	 echo '<td>'.$aktiv.'</td>
	 </tr>';	
	}	
} else {
	echo '<tr><td colspan="5" align="center">Még nem rögzített mérõt.</td></tr>';
}
	echo '<tr>
	 <td colspan="6">
		
		<!-- ### Új mérõ hozzáadása ### -->
		 
		 <div id="uj" style="display:none;height:170px;float:left;">
		 <form action="" method="post">
			 <h1>Új mérõ adatainak rögzítése</h1><br>
	
			 <input type="hidden" name="id_ceg" value="0">
			 <input type="hidden" name="kozmu" value="'.$k['id_kozmu'].'">
			 <input type="hidden" name="aktiv" value="igen">
			 
			 <div align="right">
			  <table class="prettyTable" style="margin-right:-1px;">';
			    echo '<tr><td><b>Közmû:</b></td><td>
				<select name="kozmu" class="inputPretty" style="width:224px;text-indent:1px;">';				
				$sql_k = mysql_query("SELECT * FROM kozmu");
				while($k = mysql_fetch_array($sql_k)){
					echo '<option value="'.$k['id_kozmu'].'">'.$k['kozmu'].'</option>';
				}				
				echo '</select>';				
				echo '</td></tr>';
				echo '<tr><td><b>Mérõazonosító:</b></td><td><input type="text" name="meroazon" required class="inputPretty"></td></tr>';
				/*
				echo '<tr><td><b>Megnyitva</b></td><td>
				<select name="nyito_ev">';				
				for($i=2014; $i<=$ev; $i++) {
					if($i==date('Y')) echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
					else echo '<option value="'.$i.'">'.$i.'</option>';
				}
				echo '</select>
				<select name="nyito_ho">';				
				for($j=1;$j<=12;$j++) {
					if($j==date('m')) echo '<option value="'.$j.'" selected="selected">'.$ho[$j].'</option>';
					else echo '<option value="'.$j.'">'.$ho[$j].'</option>';
				}				
				echo '</select></td></tr>';
				*/
				echo '<tr><td><b>Nyitó állás:</b></td><td><input type="text" name="nyito_allas" class="inputPretty"></td></tr>';			
				echo '</table></div>
				<input type="submit" name="submitUjMero" value="" class="saveBtnPretty">
			</form>		  
		 </div>
		 
		 <!-- <a href="#uj" rel="prettyPhoto"><input type="button" value="+ mérõ hozzáadása"></a> -->
		 
	 </td>
	</tr>';
	echo '</table>';	
}


function getMero() {
	
	$ev = date('Y');
	$ev++;
	
	$ho = array('', 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');	
	
	echo '<div class="lista">';
		 echo '<table id="oraAdatlap" cellspacing="0">';
		 $sql3 = mysql_query("SELECT * FROM mero WHERE id_mero='".$_GET['id']."'");		
		
		echo '<tr style="background-color:#bdd07f;">
				 <td width="160px"><b>Mérõazonosító</b></td>
				 <td width="120px" style="background:#6e9d2f;color:#000;"><b>Megnyitva</b></td>
				 <td width="120px"><b>Nyitó állás</b></td>				 
				 <td width="120px" style="background:#6e9d2f;color:#000;"><b>Lezárva</b></td>
				 <td width="120px"><b>Záró állás</b></td>
				 <td width="120px" style="background:#6e9d2f;color:#000;"><b>Aktív</b></td>
				 <td class="actions noprint"></td>
				 <td class="actions noprint"></td>
				 <td class="actions noprint"></td>
				</tr>';
		  
		$m = mysql_fetch_array($sql3);
		$sql1 = mysql_query("SELECT * FROM kozmu WHERE id_kozmu='".$m['kozmu']."'");
		$k = mysql_fetch_array($sql1);
		
			echo '<tr>
				 <td>'.$m['meroazon'].'</td>
				 <td>'.$m['nyito_ev'].'. '.strtolower($ho[$m['nyito_ho']]).'</td>
				 <td>'.$m['nyito_allas'].' '.$k['mertekegyseg'].'</td>';
				 
			if($m['zaro_ev']=="0") { 
			 $zaro_datum = "";
			 $zaro_allas = "";
			} else {
			 $zaro_datum = $m['zaro_ev'].'. '.strtolower($ho[$m['zaro_ho']]);
			 $zaro_allas = $m['zaro_allas'].' '.$k['mertekegyseg'];				
			}
			
			$id_fogy = "";
			$sql4 = mysql_query("SELECT * FROM fogyasztas WHERE id_mero='".$m['id_mero']."' ORDER BY id_fogy ASC LIMIT 0,1");
			$fogy = mysql_num_rows($sql4);
			if($fogy>0) {
				$f = mysql_fetch_array($sql4);
				$id_fogy = $f['id_fogy'];
			} else $id_fogy = "0";	
			
			echo '<td>'.$zaro_datum.'</td>
				  <td>'.$zaro_allas.'</td>';
				  
			if($m['aktiv']=="nem") $aktiv= '<font color="red">'.$m['aktiv'].'</font>';
	        else $aktiv = $m['aktiv'];	  
			echo '<td class="lathato">'.$aktiv.'
			
		 <!-- ### Mérõ szerkesztése ### -->
		 
		 <div id="editMero'.$m['id_mero'].'" style="display:none;height:125px;float:left;">
		 <form action="" method="post">
			 <h1>'.$k['kozmu'].' mérõ adatainak szerkesztése</h1><br>
	
			 <input type="hidden" name="id_ceg" value="'.$m['id_ceg'].'">
			 <input type="hidden" name="id_mero" value="'.$m['id_mero'].'">
			 <input type="hidden" name="id_fogy" value="'.$id_fogy.'">
			 <input type="hidden" name="nyito_ev" value="'.$m['nyito_ev'].'">
			 <input type="hidden" name="nyito_ho" value="'.$m['nyito_ho'].'">
			 <input type="hidden" name="aktiv" value="'.$m['aktiv'].'">
			 
			 <div align="right">
			  <table class="prettyTable">';
			    echo '<tr><td><b>Mérõazonosító:</b></td><td><input type="text" name="meroazon" value="'.$m['meroazon'].'" required class="inputPretty"></td></tr>';				
				echo '<tr><td><b>Nyitó állás:</b></td><td><input type="text" name="nyito_allas" value="'.$m['nyito_allas'].'" class="inputPretty"></td></tr>';				
				echo '</table></div><input type="submit" name="submitEditMero" value="" class="saveBtnPretty">
			</form>		  
		 </div>
		 
		    <!-- ### Mérõ törlése ### -->	  
	  
		    <div id="deleteMero'.$m['id_mero'].'" style="display:none;height:160px;float:left;">
			 <form action="" method="post">
			  <h1>Mérõ törlése</h1><br>		
			  
			  <p>Biztosan törli ezt a mérõt: <span><b>'.$m['meroazon'].'</b></span>?</p>
			  
			  <p>Amennyiben a törlés gombra kattint, a mérõ adataival együtt a fogyasztási adatok is törlésre kerülnek. <input type="hidden" name="id_mero" value="'.$m['id_mero'].'"> <input type="hidden" name="id_ceg" value="'.$t['id_ceg'].'"></p>
			  
			  <div align="right"><table class="prettyTable" style="margin-right:-1px;"><tr><td>Jelszó:&nbsp;</td><td><input type="password" name="password" class="inputPretty"></td></tr></table></div>
			  <input type="submit" name="submitDeleteMero" value="" class="deleteBtnPretty">
			 </form>
			</div>		    
			
			</td>
				 <td class="actions">';
				 
		   echo '</td>
				 <td class="actions"><a href="#editMero'.$m['id_mero'].'" rel="prettyPhoto"><img src="images/edit.png" class="icon" title="Szerkesztés"></a></td>
				 <td class="actions"><a href="#deleteMero'.$m['id_mero'].'" rel="prettyPhoto"><img src="images/delete.png" class="icon" title="Törlés"></a></td>
				</tr>';
		 echo '</table>
		 </div>
		 ';
		 //echo '<p class="noprint"><a href="?p=merok">vissza</a></p>';	
		  
}



function getCeg() {
	
	$ev = date('Y');
	$ev++;
		  
	$ho = array('', 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');
	
	$sql = mysql_query("SELECT * FROM ceg WHERE id_ceg='".$_GET['id']."'");
	$t = mysql_fetch_array($sql);
	
	echo '<div class="szuro noprint" style="background-image:url(images/back-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:8px;margin-right:0px;margin-bottom:8px;" onClick="window.location.href=\'index.php?p=cegek\';">
     <div class="parameter" style="width:90px;">Vissza</div>
    </div>';
	
	echo '<div class="szuro noprint" style="background-image:url(images/print.gif);background-size:auto 55%;background-position:left center;float:right;margin-top:8px;margin-right:10px;margin-bottom:8px;" onClick="window.print();">
     <div class="parameter" style="width:90px;">Nyomtatás</div>
    </div><div class="clear"></div>';
	echo '<h1>'.$t['cegnev'];	
	echo '<table id="cegKezeloIkonok" style="float:right;margin-right:50px;"><tr>	 
	 <td class="actions"><a href="#editCeg'.$t['id_ceg'].'" rel="prettyPhoto"><img src="images/edit.png" class="icon" title="Szerkesztés"></a></td>
	 <td class="actions"><a href="#deleteCeg'.$t['id_ceg'].'" rel="prettyPhoto"><img src="images/delete.png" class="icon" title="Törlés"></a>
	 
	  <!-- ### Cég szerkesztése ### -->

	  <div id="editCeg'.$t['id_ceg'].'" style="display:none;height:210px;float:left;">
			<form action="" method="post">
			 <h1>Ügyféladatok szerkesztése</h1><br>		
			 <div align="right">
			  <table class="prettyTable" style="margin-right:-1px;">
			   <tr><td><b>Ügyfél neve:</b></td><td><input type="text" name="cegnev" value="'.$t['cegnev'].'" required class="inputPretty"> <input type="hidden" name="id_ceg" value="'.$t['id_ceg'].'" class="inputPretty"></td></tr>';
	echo 	  '<tr><td><b>Telephely, terület:</b></td><td>';
				selectTelephely($t['telephely']);
	echo 	  '</td></tr>';
	echo      '<tr><td><b>Megnyitva:</b></td><td>				
				<select name="nyito_ho" class="inputPretty" style="width:107px;text-indent:1px;">';				
				for($j=1;$j<=12;$j++) {
					if($t['nyito_ho']==$j) echo '<option value="'.$j.'" selected="selected">'.$ho[$j].'</option>';
					else echo '<option value="'.$j.'">'.$ho[$j].'</option>';
				}				
	echo      '</select>
			   <select name="nyito_ev" class="inputPretty" style="width:107px;margin-right:10px;text-indent:1px;">';				
				for($i=2014; $i<=$ev; $i++) {
					if($t['nyito_ev']== $i) echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
					else echo '<option value="'.$i.'">'.$i.'</option>';
				}
				echo '</select>
			 </td></tr>';
	/*
	echo      '<tr><td><b>Lezárva</b></td><td>
				<select name="zaro_ev">
				<option value="0"></option>';				
				for($i=2014; $i<=$ev; $i++) {
					if($t['zaro_ev'] == $i) echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
					else echo '<option value="'.$i.'">'.$i.'</option>';
				}
				echo '</select>
				<select name="zaro_ho">
				<option value="0"></option>';				
				for($j=1;$j<=12;$j++) {
					if($t['zaro_ho']==$j) echo '<option value="'.$j.'" selected="selected">'.$ho[$j].'</option>';
					else echo '<option value="'.$j.'">'.$ho[$j].'</option>';
				}				
	echo      '</select></td></tr>';
	*/
	echo 	  '<tr><td><b>Lezárva:</b></td><td>
			   <select name="aktiv" class="inputPretty" style="width:224px;text-indent:1px;">';
			    if($t['aktiv']=="nem") echo '<option value="nem" selected="selected">Igen</option>
				<option value="igen">Nem</option>';
				else echo '<option value="nem">Igen</option>
				<option value="igen" selected="selected">Nem</option>';
	echo      '</select>
			   </td></tr>';
	/*
	echo 	  '<tr><td><b>Listában megjelenik?</b></td><td>
			   <select name="aktiv">';
			    if($t['aktiv']=="igen") echo '<option value="igen" selected="selected">igen</option>
				<option value="nem">nem</option>';
				else echo '<option value="igen">igen</option>
				<option value="nem" selected="selected">nem</option>';
	echo      '</select>
			   </td></tr>';
	*/		   
	echo	  '</table></div>
			  <input type="submit" name="submitEditCeg" value="" class="saveBtnPretty">
		</form>
	  </div>
	  
	  <!-- ### Cég törlése ### -->	  
	  
	  <div id="deleteCeg'.$t['id_ceg'].'" style="display:none;height:160px;float:left;">
		<form action="" method="post">
		<h1>Ügyféladatok törlése</h1><br>		
		<p>Biztosan törli az alábbi ügyfél adatait: <span><b>'.$t['cegnev'].'</b></span>?</p>
		<p>Amennyiben a törlés gombra kattint, az ügyfélhez kapcsolódó minden adat törlésre kerül.</p>		
		<div align="right"><table class="prettyTable"><tr><td>Jelszó: </td><td><input type="password" name="password" class="inputPretty"> <input type="hidden" name="id_ceg" value="'.$t['id_ceg'].'"></td></tr></table>
		<input type="submit" name="submitDeleteCeg" value="" class="deleteBtnPretty"></div>
		</form>
	  </div>	  
	 </td>
	 </tr></table>';
	 
	echo '<div class="jobbra" style="float:right;margin-right:192px;font-size:13px;color:#232323;">';
	 if($t['nyito_ev']!="0") echo '<div style="float:left;width:243px;"><b>Megnyitva:</b> '.$t['nyito_ev']." ".$ho[$t['nyito_ho']]."</div>"; else echo '<div style="float:left;width:243px;"><b>Megnyitva:</b></div>';
	 if($t['zaro_ev']!="0") echo '<div style="float:left;width:200px;"><b>Lezárva:</b> '.$t['zaro_ev']." ".$ho[$t['zaro_ho']]."</div>";
	 else echo '<div style="float:left;width:200px;"><b>Lezárva:</b> </div>';
	echo '</div>';
	
	echo '</h1>';		
	
	// Közmûvek listázása
	$sql2 = mysql_query("SELECT * FROM kozmu");
	while($k = mysql_fetch_array($sql2)) {
		 
		 // Adott közmûhöz tartozó mérõk megszámlálása
		 $sql3 = mysql_query("SELECT * FROM mero WHERE kozmu='".$k['id_kozmu']."' AND id_ceg='".$t['id_ceg']."'");
		 $n = mysql_num_rows($sql3);
		 if($n==0) $class = "noprint"; else $class="";
		 
		echo '<div class="lista">';
		echo '<p style="margin:5px" class="'.$class.'"><b>'.$k['kozmu'].'</b></p>';
		 echo '<table  id="cegAdatlap" cellspacing="0">';
		 
		 if($n>0) {
			echo '<tr style="background-color:#bdd07f;">
				 <td width="160px"><b>Mérõazonosító</b></td>
				 <td width="120px" style="background:#6e9d2f;color:#000;"><b>Megnyitva</b></td>
				 <td width="120px"><b>Nyitó állás</b></td>				 
				 <td width="120px" style="background:#6e9d2f;color:#000;"><b>Lezárva</b></td>
				 <td width="120px"><b>Záró állás</b></td>
				 <td width="120px" style="background:#6e9d2f;color:#000;"><b>Aktív</b></td>
				 <td class="actions noprint"></td>
				 <td class="actions noprint"></td>
				 <td class="actions noprint"></td>
				</tr>';
		  
		  $c = 1;
		  while($m = mysql_fetch_array($sql3)) {
			if($c%2==0) $row = "alt-row"; else $row="";  
			echo '<tr class="'.$row.'">
				 <td>'.$m['meroazon'].'</td>
				 <td>'.$m['nyito_ev'].'. '.strtolower($ho[$m['nyito_ho']]).'</td>
				 <td>'.$m['nyito_allas'].' '.$k['mertekegyseg'].'</td>';
				 
			if($m['zaro_ev']=="0") { 
			 $zaro_datum = "";
			 $zaro_allas = "";
			} else {
			 $zaro_datum = $m['zaro_ev'].'. '.strtolower($ho[$m['zaro_ho']]);
			 $zaro_allas = $m['zaro_allas'].' '.$k['mertekegyseg'];				
			}
			
			$id_fogy = "";
			$sql4 = mysql_query("SELECT * FROM fogyasztas WHERE id_mero='".$m['id_mero']."' ORDER BY id_fogy ASC LIMIT 0,1");
			$fogy = mysql_num_rows($sql4);
			if($fogy>0) {
				$f = mysql_fetch_array($sql4);
				$id_fogy = $f['id_fogy'];
			} else $id_fogy = "0";	
			
			echo '<td>'.$zaro_datum.'</td>
				  <td>'.$zaro_allas.'</td>';
				  
			if($m['aktiv']=="nem") $aktiv= '<font color="red">'.$m['aktiv'].'</font>';
	        else $aktiv = $m['aktiv'];	  
			echo '<td class="lathato">'.$aktiv.'
			
		 <!-- ### Mérõ szerkesztése ### -->
		 
		 <div id="editMero'.$m['id_mero'].'" style="display:none;height:170px;float:left;">
		 <form action="" method="post">
			 <h1>'.$k['kozmu'].' mérõ adatainak szerkesztése</h1><br>
	
			 <input type="hidden" name="id_ceg" value="'.$t['id_ceg'].'">
			 <input type="hidden" name="id_mero" value="'.$m['id_mero'].'">
			 <input type="hidden" name="id_fogy" value="'.$id_fogy.'">
			 <input type="hidden" name="aktiv" value="'.$m['aktiv'].'">
			 
			 <div align="right">
			  <table class="prettyTable" style="margin-right:-1px;">';
			    echo '<tr><td><b>Mérõazonosító:</b></td><td><input type="text" name="meroazon" value="'.$m['meroazon'].'" required class="inputPretty"></td></tr>';
				echo '<tr><td><b>Megnyitva:</b></td><td>				
				<select name="nyito_ho" class="inputPretty" style="width:107px;text-indent:1px;">';				
				for($j=1;$j<=12;$j++) {
					if($m['nyito_ho']==$j) echo '<option value="'.$j.'" selected="selected">'.$ho[$j].'</option>';
					else echo '<option value="'.$j.'">'.$ho[$j].'</option>';
				}				
				echo '</select>
					  <select name="nyito_ev" class="inputPretty" style="width:107px;margin-right:10px;text-indent:1px;">';				
				for($i=2014; $i<=$ev; $i++) {
					if($m['nyito_ev']== $i) echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
					else echo '<option value="'.$i.'">'.$i.'</option>';
				}
				echo '</select>
				</td></tr>';
				echo '<tr><td><b>Nyitó állás:</b></td><td><input type="text" name="nyito_allas" value="'.$m['nyito_allas'].'" class="inputPretty"></td></tr>';								
				echo '</table></div>
				<input type="submit" name="submitEditMero" value="" class="saveBtnPretty">
			</form>		  
		 </div>		    

		    <!-- ### Mérõ lezárása ### -->	  
	  
		    <div id="closeMero'.$m['id_mero'].'" style="display:none;height:110px;float:left;">
			 <form action="" method="post">
			  <h1>Mérõ lezárása</h1><br>		
			  
			  <p>Biztosan lezárja ezt a mérõt: <span><b>'.$m['meroazon'].'</b></span>?</p>
			  
			  <p>Amennyiben a "Mentés" gombra kattint, a mérõ a legutóbb rögzített fogyasztási adatokkal lezárásra kerül. <input type="hidden" name="id_mero" value="'.$m['id_mero'].'"> <input type="hidden" name="id_ceg" value="'.$t['id_ceg'].'"></p>
			  
			  <p><input type="submit" name="submitCloseMero" value="" class="saveBtnPretty"></p>
			 </form>
			</div>

		    <!-- ### Mérõ megnyitása ### -->	  
	  
		    <div id="openMero'.$m['id_mero'].'" style="display:none;height:110px;float:left;">
			 <form action="" method="post">
			  <h1>Mérõ megnyitása</h1><br>		
			  
			  <p>Biztosan újra meg kívánja nyitni ezt a mérõt: <span><b>'.$m['meroazon'].'</b></span>?</p>
			  
			  <p>Amennyiben a "Mentés" gombra kattint, a mérõhöz további fogyasztási adatokat rögzíthet. <input type="hidden" name="id_mero" value="'.$m['id_mero'].'"> <input type="hidden" name="id_ceg" value="'.$t['id_ceg'].'"></p>
			  
			  <p><input type="submit" name="submitOpenMero" value="" class="saveBtnPretty"></p>
			 </form>
			</div>				
			
			
			</td>
				 <td class="actions">';
				 
		   echo '</td>
				 <td class="actions"><a href="#editMero'.$m['id_mero'].'" rel="prettyPhoto"><img src="images/edit.png" class="icon" title="Szerkesztés"></a></td>
				 <td class="actions">';
				 if($m['zaro_ev']!='0') {
				  echo '<a href="#openMero'.$m['id_mero'].'" rel="prettyPhoto"><img src="images/open.png" class="icon" title="Megnyitás"></a>';
				 } else {
				  echo '<a href="#closeMero'.$m['id_mero'].'" rel="prettyPhoto"><img src="images/close.png" class="icon" title="Lezárás"></a>';	 
				 }				 
				 echo '</td>				 
				</tr>';
			$c++;	
		  }
		 } else {
		  echo '<tr class="'.$class.'"><td>Ehhez a közmûhöz nem tartoznak mérõk.</td></tr>';	 
		 }
		 echo '<tr id="ujMeroSor"><td colspan="9">
		 
		 <!-- ### Új mérõ hozzáadása ### -->
		 
		 <div id="uj'.$k['id_kozmu'].'" style="display:none;height:130px;float:left;">
		 <form action="" method="post">
			 <h1>Új '.strtolower($k['kozmu']).' mérõ adatainak rögzítése</h1><br>
	
			 <input type="hidden" name="id_ceg" value="'.$t['id_ceg'].'">
			 <input type="hidden" name="kozmu" value="'.$k['id_kozmu'].'">
			 <input type="hidden" name="aktiv" value="igen">
			 
			 <div align="right">
			  <table class="prettyTable">';
			    echo '<tr>
						<td><b>Mérõazonosító:</b></td>
						<td>
						 <select name="id_mero" id="myselect" class="inputPretty" style="width:224px;text-indent:1px;">';
							echo '<option>-- Órák --</option>';
						 $sql_m = mysql_query("SELECT * FROM mero WHERE id_ceg='0' AND aktiv='igen' AND kozmu='".$k['id_kozmu']."'");
						 while ($select = mysql_fetch_array($sql_m)) {
							echo '<option value="'.$select['id_mero'].'">'.$select['meroazon'].'</option>';		
						 }
				echo	'</select>
					    </td>
					  </tr>';
				echo '<tr><td><b>Megnyitva:</b></td><td>				
				<select name="nyito_ho" class="inputPretty" style="width:107px;text-indent:1px;">';				
				for($j=1;$j<=12;$j++) {
					echo '<option value="'.$j.'">'.$ho[$j].'</option>';
				}				
				echo '</select>
					  <select name="nyito_ev" class="inputPretty" style="width:107px;margin-right:10px;text-indent:1px;">';				
				for($i=2014; $i<=$ev; $i++) {
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
				echo '</select>
				</td></tr>';							
				echo '</table></div><input type="submit" name="submitAddMero" value="" class="saveBtnPretty">
			</form>		  
		 </div>
		 
		 <div class="szuro noprint" style="background-image:url(images/next-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:3px;margin-right:2px;margin-bottom:2px;width:202px;">
     <div class="parameter" style="width:160px;text-indent:4px;"><a href="#uj'.$k['id_kozmu'].'" rel="prettyPhoto" style="color:#232323">Mérõ hozzáadása</a></div>
		 
		 <!--<a href="#uj'.$k['id_kozmu'].'" rel="prettyPhoto" class="ujMeroGomb"><input type="button" value="+ mérõ hozzáadása"></a>-->
		 </td></tr>';	
		 echo '</table>';
		echo '</div>';
	}
	//echo '<p class="noprint"><a href="?p=cegek">vissza</a></p>';
}


function setMero() {
	if(isset($_POST['submitUjMero'])) {
		
		$sql = "INSERT INTO mero(id_mero, id_ceg, meroazon, kozmu, nyito_ev, nyito_ho, nyito_allas, aktiv) VALUES(NULL,'".mysql_real_escape_string($_POST['id_ceg'])."','".mysql_real_escape_string($_POST['meroazon'])."','".mysql_real_escape_string($_POST['kozmu'])."','".mysql_real_escape_string($_POST['nyito_ev'])."','".mysql_real_escape_string($_POST['nyito_ho'])."','".mysql_real_escape_string($_POST['nyito_allas'])."','".mysql_real_escape_string($_POST['aktiv'])."')";
		
		mysql_query($sql);
		
		$sql = mysql_query("SELECT MAX(id_mero) FROM mero");
		$m = mysql_fetch_array($sql);		
		
		$sql2 = "INSERT INTO fogyasztas(id_fogy,id_mero,ev,ho,nyito) VALUES(NULL,'".$m[0]."','".mysql_real_escape_string($_POST['nyito_ev'])."','".mysql_real_escape_string($_POST['nyito_ho'])."','".mysql_real_escape_string($_POST['nyito_allas'])."')";
		
		mysql_query($sql2);
		
		if($_POST['id_ceg']=="0") header("Location:?p=merok");
		else header("Location:?p=ceg&id=".$_POST['id_ceg']);
		
	}	
}
setMero();

function updateMero() {
	if(isset($_POST['submitEditMero'])) {
		if(!mysql_query("UPDATE mero SET meroazon='".mysql_real_escape_string($_POST['meroazon'])."', id_ceg='".mysql_real_escape_string($_POST['id_ceg'])."', nyito_ev='".mysql_real_escape_string($_POST['nyito_ev'])."', nyito_ho='".mysql_real_escape_string($_POST['nyito_ho'])."', nyito_allas='".mysql_real_escape_string($_POST['nyito_allas'])."', aktiv='".mysql_real_escape_string($_POST['aktiv'])."' WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."'")) echo mysql_error();
		
		if($_POST['id_fogy']!="0") {
		 mysql_query("UPDATE fogyasztas SET ev='".mysql_real_escape_string($_POST['nyito_ev'])."', ho='".mysql_real_escape_string($_POST['nyito_ho'])."', nyito='".mysql_real_escape_string($_POST['nyito_allas'])."' WHERE id_fogy='".mysql_real_escape_string($_POST['id_fogy'])."'");		 
		} else {
		 mysql_query("INSERT INTO fogyasztas(id_fogy,id_mero,ev,ho,nyito) VALUES(NULL,'".mysql_real_escape_string($_POST['id_mero'])."','".mysql_real_escape_string($_POST['nyito_ev'])."','".mysql_real_escape_string($_POST['nyito_ho'])."','".mysql_real_escape_string($_POST['nyito_allas'])."')");	
		}
		header("Location:?p=mero&id=".$_POST['id_mero']);
	}
}
updateMero();

function addMero() {
	if(isset($_POST['submitAddMero'])) {
		if(!mysql_query("UPDATE mero SET id_ceg='".mysql_real_escape_string($_POST['id_ceg'])."', nyito_ev='".mysql_real_escape_string($_POST['nyito_ev'])."', nyito_ho='".mysql_real_escape_string($_POST['nyito_ho'])."' WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."'")) echo mysql_error();		
		header("Location:?p=ceg&id=".$_POST['id_ceg']);
	}
}
addMero();

function deleteMero() {
	if(isset($_POST['submitDeleteMero'])) {
		$sql = mysql_query("SELECT * FROM user WHERE password='".md5($_POST['password'])."'");
		$n = mysql_num_rows($sql);
		if($n>0) {
		mysql_query("DELETE FROM mero WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."'");
		mysql_query("DELETE FROM fogyasztas WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."'");
		header("Location:?p=merok");
		} else header('Location:login.php?error=bad_pswd');
	}		
}
deleteMero();

function closeMero() {
	if(isset($_POST['submitCloseMero'])) {
		
		$sql = "SELECT * FROM fogyasztas WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."' ORDER BY id_fogy DESC LIMIT 0,1";
		$q = mysql_query($sql);
		$f = mysql_fetch_array($q);
		
		if($f['zaro']=="0") {
			mysql_query("UPDATE fogyasztas SET zaro='".$f['nyito']."' WHERE id_fogy='".$f['id_fogy']."'");
			mysql_query("UPDATE mero SET zaro_ev='".$f['ev']."', zaro_ho='".$f['ho']."', zaro_allas='".$f['nyito']."' WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."'");
		} else {
			mysql_query("UPDATE mero SET zaro_ev='".$f['ev']."', zaro_ho='".$f['ho']."', zaro_allas='".$f['zaro']."' WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."'");
		}
		
		header("Location:?p=ceg&id=".$_POST['id_ceg']);
	}
}
closeMero();

function openMero() {
	if(isset($_POST['submitOpenMero'])) {		
		
	 mysql_query("UPDATE mero SET zaro_ev='', zaro_ho='', zaro_allas='' WHERE id_mero='".mysql_real_escape_string($_POST['id_mero'])."'");		
		
	 header("Location:?p=ceg&id=".$_POST['id_ceg']);
	 
	}
}
openMero();


/* ### ÁRAK kezelése ### */
function getAr($kozmu,$ev,$ho) {
	$sql = mysql_query("SELECT * FROM arak WHERE kozmu='$kozmu' AND ev='$ev' AND ho='$ho'");
	$ar = mysql_fetch_array($sql);
	if($ar['egysegar']=="") return('<a href="#arak'.$kozmu.'" rel="prettyPhoto">nincs adat</a>'); else return('<a href="#arak'.$kozmu.'" rel="prettyPhoto">'.$ar['egysegar'].',-Ft</a>');
}

function getArak($kozmu,$kozmunev,$ev) {

    for($i=0;$i<=12;$i++) {
	 $arak[$i] = 0;
	 $arak_id[$i]=0;
	}
	
	$ho = array('Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');	
	

	 $sql = mysql_query("SELECT * FROM arak WHERE ev='".$ev."' AND kozmu LIKE '".$kozmu."' ORDER BY ho");
	 while($ar = mysql_fetch_array($sql)) {
	  $h = $ar['ho'];
	  $arak[$h-1] = $ar['egysegar'];
	  $arak_id[$h-1] = $ar['id_ar'];
	 }	
    
	echo '	
	<form method="post" action="">
	<h1>'.$kozmunev.' árak szerkesztése ('.$_SESSION['ev'].')</h2>
	<input type="hidden" name="kozmu" value="'.$kozmu.'">
	<input type="hidden" name="ev" value="'.$ev.'">
	<table width="100%" style="float:right;margin-bottom:8px;">
	 <tr>
	  <td><b>Hónap</b></td>
	  <td><b>Egységár &nbsp;</b></td>
	  <td rowspan="13" style="width:222px;vertical-align:top;background-color:#ebebeb;">
	   <div style="width:200px;margin:10px;">
	    <p><b>Szolgáltatás árbeállításai</b></p>
		<p>A szerkeszteni kívánt árérték cellájába írja be a megfelelõ értéket, majd kattintson az "Árak mentése" gombra. Mentéskor minden cella tartalma rögzítésre kerül, ezért mentés elõtt figyelmesen nézze át az adott évre vonatkozó árakat.</p>
		<p>Ha nem kíván változtatni az értékeken, zárja be ezt az ablakot.</p>
	   </div>
	  </td>
	</tr>';
	for($j=0;$j<12;$j++) {
	 echo '<tr>';	 
	 echo    '<td>'.$ho[$j].' <input type="hidden" name="id_'.$j.'" value="'.$arak_id[$j].'"></td>
	          <td><input type="text" name="ar_'.$j.'" value="'.$arak[$j].'" maxlength="4" class="egysegar"> Ft</td>';
	 echo '</tr>';	 
	}
	echo '</table>
	<div class="clear"></div>
	<input name="arakSubmit" type="submit" value="" class="saveBtnPretty">
	</form>';
}

function setAr() {
	if(isset($_POST['arakSubmit'])) {
	 for($i=0;$i<12;$i++) {
	 if($_POST['id_'.$i]!='0') {
	  $sql = "UPDATE arak SET egysegar='".$_POST['ar_'.$i]."' WHERE id_ar='".$_POST['id_'.$i]."'"; 
	 } else {
	  $sql = "INSERT INTO  `arak` (
`id_ar` ,
`kozmu` ,
`ev` ,
`ho`,
`egysegar`
)
VALUES (
NULL , '".$_POST['kozmu']."', '".$_POST['ev']."', '".($i+1)."', '".$_POST['ar_'.$i]."');";	  
	 }
	
	 if(!mysql_query($sql)) echo mysql_error();
	 else header('Location:?p=kozmuvek');
	} 
 }
}
setAr();




/* ##### FOGYASTÁS KEZELÉSÉNEK FÜGGVÉNYEI INNEN ##### */

// Aktív telephely beállítása
if(!isset($_SESSION['telephely'])) { 
 $sqlt = mysql_query("SELECT * FROM telephely ORDER BY id_telephely ASC LIMIT 0,1");
 $th = mysql_fetch_array($sqlt); 
 $_SESSION['telephely']=$th['id_telephely'];
 $_SESSION['telephely_cim']=$th['cim'];
} 
if(isset($_GET['telephely'])){
	$_SESSION['telephely']=$_GET['telephely'];
	$_SESSION['telephely_cim'] = jquery2iso($_POST['text']);
}

// Aktív közmû beállítása
if(!isset($_SESSION['kozmu'])) {
  $sqlk = mysql_query("SELECT * FROM kozmu ORDER BY id_kozmu ASC LIMIT 0,1");
  $tk = mysql_fetch_array($sqlk);
  $_SESSION['kozmu']=$tk['id_kozmu'];
  $_SESSION['kozmu_nev']=$tk['kozmu'];
}
if(isset($_GET['kozmu'])) {
	$_SESSION['kozmu']=$_GET['kozmu'];
	$_SESSION['kozmu_nev'] = jquery2iso($_POST['text']);
}

// Aktív év beállítása
if(!isset($_SESSION['ev'])) $_SESSION['ev']=date('Y');
if(isset($_GET['ev'])) {
	$_SESSION['ev']=$_GET['ev'];
}

// Menük
function telephelyvalszto() {
	$sql = mysql_query("SELECT * FROM telephely ORDER BY cim");	
	while($t = mysql_fetch_array($sql)) {
	 if($_SESSION['telephely']==$t['id_telephely']) $selected = ' class="aktiv"'; else $selected = '';	
	 echo '<a href="'.$t['id_telephely'].'" rel="telephely"'.$selected.'>'.$t['cim'].'</a>';	
	}
}

function kozmuvalszto() {
	$sql = mysql_query("SELECT * FROM kozmu ORDER BY id_kozmu");	
	while($t = mysql_fetch_array($sql)) {
	 if($_SESSION['kozmu']==$t['id_kozmu']) $selected = ' class="aktiv"'; else $selected = '';	
	 echo '<a href="'.$t['id_kozmu'].'" rel="kozmu"'.$selected.'>'.$t['kozmu'].'</a>';	
	}	
}

function cegvalszto() {
	// Tárgyév
	$ev = $_SESSION['ev'];
	
	$sql = mysql_query("SELECT * FROM ceg WHERE telephely='".$_SESSION['telephely']."' ORDER BY sorrend");
	$i = 1;
	while($t = mysql_fetch_array($sql)) {
		
	 // Listázás csak akkor, ha az ügyfél rendelkezik az adott közmûhöz aktív mérõvel 
	 $sql2 = mysql_query("SELECT * FROM mero WHERE id_ceg='".$t['id_ceg']."' AND kozmu='".$_SESSION['kozmu']."' AND `nyito_ev`<='$ev' AND (`zaro_ev`='0' OR `zaro_ev`>='$ev') AND aktiv LIKE 'igen'");
	 $n = mysql_num_rows($sql2);
	 if($n>0) {
	  if($i==1) $class = 'class="aktiv"'; else $class = '';
	  echo '<a href="'.$t['id_ceg'].'" id="'.$i.'" '.$class.' title="'.$t['cegnev'].'">'.$t['cegnev'].'</a>';	 
	  $i++;
	 }
	 
	}

	echo '<a href="0" id="'.$i.'">Összesen</a>';	
}


/*
#######################################################################
				FOGYASZTÁS BEVITELI TÁBLÁZATAI
#######################################################################
*/

function getCegtabla() {
	
/* ### Legfontosabb inputok ### */
	
	// Tárgyév
	$ev = $_SESSION['ev'];
	
	// Közmû id
	$kozmu = $_SESSION['kozmu'];
	
	// Közmû mértékegység lekérdezése
	  if(!$sql = mysql_query("SELECT * FROM kozmu WHERE id_kozmu ='$kozmu'")) echo mysql_error();
	  $k = mysql_fetch_array($sql);	
	$mertekegyseg = $k['mertekegyseg'];		
	
	// Telephely id
	$telephely = $_SESSION['telephely'];    

	 
	$ho = array('Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');
	 
	$sql = mysql_query("SELECT * FROM ceg WHERE telephely='$telephely' ORDER BY sorrend");
	 
	$i=1;	 
	while($c=mysql_fetch_array($sql))
	{
      //$i= $c['sorrend'];
	  
	  if($i==1) echo '<div class="szuro noprint" style="background-image:url(images/print.gif);background-size:auto 55%;background-position:left center;float:right;margin-left:-200px;margin-top:8px;" onClick="$(\'.cegTabla\').removeClass(\'toPrint\');$(\'#ceg'.$c['id_ceg'].'\').addClass(\'toPrint\');$(\'#ceg'.$c['id_ceg'].'\').removeClass(\'page\');window.print();">
  <div class="parameter" style="width:90px;">Nyomtatás</div>
 </div>';
	  
	  $sql2 = mysql_query("SELECT * FROM mero WHERE id_ceg='".$c['id_ceg']."' AND kozmu LIKE '$kozmu' AND `nyito_ev`<='$ev' AND (`zaro_ev`='0' OR `zaro_ev`>='$ev') ORDER BY id_mero");
	  $n = mysql_num_rows($sql2); // Órák száma adott cégnél
	  
	  if($n>0) { // Ha nincs ilyen közmûve a cégnek	  
	   
	  echo '<div id="ceg'.$c['id_ceg'].'" class="cegTabla page"';	  
	  if($i=="1") echo 'style="display:block"';
	  if($c['cegnev']!="Fõmérõ") 
	  echo '><center><font style="font-size:16pt;color:#222;line-height:50px"><b>'.$c['cegnev'].'</b></font></center>';
	  else
	  echo '><center><font style="font-size:16pt;color:red;line-height:50px"><b>'.$c['cegnev'].'</b></font></center>';	 	
	  
	  //Fejléc felépítése
	  echo '<table border="0" id="kozmuTabla1" cellspacing="0" cellpadding="0px" style="margin-bottom:5px;">
			 <tr bgcolor="#BDD07F">
			  <td class="cimsor hoOszlop" '.$csere.'>';
			  if($c['id_ceg']!="30" && $c['id_ceg']!="39") echo 'Azonosító:'; 
			  echo '</td>
			  <!-- ismétlõdõ -->';			  
 
	  $y = 1;
	  while($m=mysql_fetch_array($sql2)) {

	  $oraID[$y] = $m['id_mero'];
	  if($m['nyito_ho']!="" && $m['nyito_ho']!="0" && $m['nyito_ho']<10) $m['nyito_ho'] = "0".$m['nyito_ho']; 
	  $megnyitva[$y] = $m['nyito_ev']."-".$m['nyito_ho'];
	  if($megnyitva[$y]=="0-0" || $megnyitva[$y]=="-") $megnyitva[$y] = "2014-01";
	  
	  if($m['zaro_ho']!="0" && $m['zaro_ho']<10) $m['zaro_ho'] = "0".$m['zaro_ho'];
	  $zaro_ev[$y] = $m['zaro_ev']."-".$m['zaro_ho'];
	  
	  if($m['zaro_ev']!="0") $lezarva[$y] = 1; else $lezarva[$y]=0; 	
	  
	  if($y%2!=0) $bg = 'style="background: #6e9d2f;color:#000;border-bottom:2px solid #000;text-align:center"'; else $bg='style="border-bottom:2px solid #000;text-align:center;"'; 
	  echo '<td colspan="2" '.$bg.'>'.$m['meroazon'].'</td>';
	  $y++;
	  }	  
	  
	  if($y<4) {
		for($i2=$y;$i2<=4;$i2++) {		 
		 echo '<td colspan="2" style="border-bottom:2px solid #000;text-align:center;">&nbsp;</td>'; 
		}  
	  }
	  
	  echo    '<!-- ismétlõdõ eddig -->
	  
			  <td id="potCella"></td>	
			  <td colspan="3" style="background: #6e9d2f;color:#000;border-bottom:2px solid #000;">&nbsp;</td>			  
			 </tr>		
			 <tr bgcolor="#BDD07F">
			  <td class="cimsor">Hónap</td>
			  <!-- ismétlõdõ -->';
			for($j=1;$j<=$n;$j++) {			
			if($j%2!=0) $bg = 'style="background:#6e9d2f; color:#000;"'; else $bg='';
			
			echo '<td class="cimsor zaroAllasOszlop" '.$bg.' align="center">Záró óraállás:</td>
			      <td class="cimsor mertekegysegOszlop" '.$bg.' align="center">'.$mertekegyseg.'</td>';			
			}
			if($n<4) {
			 for($j=$n;$j<4;$j++) { 						
			  echo '<td class="cimsor zaroAllasOszlop" align="center">&nbsp;</td>
			      <td class="cimsor mertekegysegOszlop" align="center">&nbsp;</td>';			
			 }
			}
			
	  echo   '<!-- ismétlõdõ eddig -->
			  <td id="potCella"></td>	
			  <td class="cimsor osszesenOszlop" style="background: #6e9d2f;color:#000;text-align:center">Összesen:</td>
			  <td class="cimsor egysegarOszlop" style="background: #6e9d2f;color:#000;text-align:center">Egység-<br>ár:</td>
			  <td class="cimsor ertekOszlop" style="background: #6e9d2f;color:#000;text-align:center">Érték:</td>
			 </tr>';	 
		 
	 $ertekEves = 0;		 
	 // Hónapok felépítése soronként
	 for($x=0;$x<12;$x++) {
		//if($x%2==0) echo	'<tr class="sotet">'; else
	    echo '<tr onMouseOver="this.style.backgroundColor=\'#fffed1\'" onMouseOut="this.style.backgroundColor=\'\'">';
	    echo	 '<td class="vilagos">'.$ho[$x].'</td>			  
			  <!-- óra miatt ismétlõdõ rész innen -->';
			  
			 $egysegar = 0;
			 $sqla = mysql_query("SELECT * FROM arak WHERE ev='$ev' AND ho='".($x+1)."' AND kozmu LIKE '$kozmu'");
			 $sqlar = mysql_fetch_array($sqla);
			 $egysegar = $sqlar['egysegar'];
			  
			 $sumHavi = 0; 
			
			for($j=1;$j<=$n;$j++)			
			{
			 
			 $cellClass = "";
			 
			 // Értékek visszakérdezése adatbázisból
			 $s=mysql_query("SELECT * FROM fogyasztas WHERE id_mero='".$oraID[$j]."' AND ev='$ev' AND ho='".($x+1)."'");
			 $fogy=mysql_fetch_array($s);
			 if(isset($fogy['id_fogy'])) {
			 $indulo = 0;
			 if($fogy['nyito']!=0) $indulo = $fogy['nyito']; else $indulo="0";
			 $zaro = 0;
			 $zaro = $fogy['zaro'];

			 if($zaro>=$indulo) 
			  { 				
				$havifogy = $zaro-$indulo; 
			  } 
			  else 
			  { 				
				$havifogy = 0; 
			  }
			  
			 } else {
			 $indulo = "";
			 $zaro = "";
			 $havifogy = 0;
			 }
			 
			 if($zaro == 0) $zaro = "";
			 
			 $sumHavi += $havifogy;
			 			 
			 //if($ho[$x]=="Január" && $j==1) $autofocus='autofocus'; else $autofocus='';		 
			 if($j%2!=0) $bg = 'bgcolor="#e8e8e8"'; else $bg=''; 
			 
			// Állás kiíratása
			 $hoX  = $x+1;
			 if($hoX<10) $hoX = "0".$hoX;
			 if($megnyitva[$j]>$ev."-".$hoX) { 
			  $visibility = "opacity:0;";
			 } else {
			  $visibility = "";	 
			 }
			 
			 if($zaro_ev[$j]<$ev."-".$hoX && $lezarva[$j] == 1) {
			   $visibility = "opacity:0;";	 
			 }
			 
			 if($lezarva[$j] == 1 || $megnyitva[$j]>$ev."-".$hoX) {
			  $readonly = 'readonly="readonly"';
			  $cellClass .= " inaktiv";
			 } else { 
			  $readonly='';
			  $cellClass .= "";
			 } 		 
			 
			 if($indulo!="" && $zaro<$indulo && $zaro!=0) $hiba = "color:#fff !important;background-color:red;";
			 else $hiba = "";
			 
		     echo '<td class="vilagos" '.$bg.'>';
			 echo '<input type="hidden" id="readonly_c'.$i.'_h'.($x+1).'_o'.$j.'" value="'.$lezarva[$j].'">';
			 echo '<span style="'.$visibility.'" id="nyito_c'.$i.'_h'.($x+1).'_o'.$j.'" title="nyito_c'.$i.'_h'.($x+1).'_o'.$j.'">'.$indulo.'</span><br>
			    <table style="width:61px;'.$visibility.'" border="0" cellpadding="0" cellspacing="0" onMouseOver="if($(\'#readonly_c'.$i.'_h'.($x+1).'_o'.$j.'\').val()!=\'1\'){if(document.formVillany.zaro_'.$oraID[$j].'_'.($x+1).'.value!=\'\'){ document.getElementById(\'uritCella_'.$oraID[$j].'_'.($x+1).'\').style.display=\'block\'}}" onMouseOut="document.getElementById(\'uritCella_'.$oraID[$j].'_'.($x+1).'\').style.display=\'none\'">
				 <tr>
				  <td class="oraallasCella'.$cellClass.'" style="'.$hiba.'">
				   <input style="'.$hiba.'" type="text" class="oraallas" id="allas_c'.$i.'_h'.($x+1).'_o'.$j.'" name="zaro_'.$oraID[$j].'_'.($x+1).'" value="'.$zaro.'" onBlur="if(!$(this).attr(\'readonly\') && $(this).val()!=\'\'){ellenoriz(\'nyito_c'.$i.'_h'.($x+1).'_o'.$j.'\',this.value); szamol(\''.$indulo.'\',this.value,\''.$i.'\',\''.$j.'\',\''.($x+1).'\',\''.$kozmu.'\',\''.$n.'\')}" maxlength="6" '.$readonly.'>
				  </td>
				  <td align="center" width="18px" class="uritCella">
				   <img id="uritCella_'.$oraID[$j].'_'.($x+1).'" src="images/delete30x28.png" title="Cella ürítése" style="width:12px;cursor:pointer;display:none" onClick="document.formVillany.zaro_'.$oraID[$j].'_'.($x+1).'.value=\'-\';$(\'#rejtett_havi_fogy_c'.$i.'_o'.$j.'_h'.($x+1).'\').val(0);szamol(\''.$indulo.'\',this.value,\''.$i.'\',\''.$j.'\',\''.($x+1).'\',\''.$kozmu.'\',\''.$n.'\');">
				  </td>
				 </tr>
				</table>				
			   </td>		  
			   <td class="vilagos" align="center" id="havi_fogy_c'.$i.'_o'.$j.'_h'.($x+1).'" '.$bg.'> <span style="'.$visibility.'">'.penzFormatum($havifogy).'<br><font class="kwh">'.$mertekegyseg.'</font></span>
			   </td>
			   <input type="hidden" id="rejtett_havi_fogy_c'.$i.'_o'.$j.'_h'.($x+1).'" value="'.$havifogy.'" style="width:30px;">';	
			   
			} // For órák száma vége
			
			if($n<4) {			  
			 for($i2=$n;$i2<4;$i2++) {
		     echo '<td class="vilagos">';			 
			 echo '<span></span><br>
			    <table border="0" cellpadding="0" cellspacing="0" width="61px">
				 <tr>
				  <td class="oraallasCella" style="border:1px solid transparent !important;background:transparent !important;">
				   &nbsp;
				  </td>
				  <td align="center" width="18px">
				   <img src="images/delete30x28.png" style="width:12px;cursor:pointer;display:none">
				  </td>
				 </tr>
				</table>				
			   </td>		  
			   <td class="vilagos" align="center"> &nbsp;<br><font class="kwh">&nbsp;</font>
			   </td>';
				}
			}
			
		
			  $ertek = $egysegar*$sumHavi;		  
		 			  
	    echo   '<!-- óra miatt ismétlõdõ rész eddig -->
			  <td id="potCella">
			  <!-- SEGÉDMEZÕK INNEN -->
			   <input type="hidden" id="egysegar_c'.$i.'_h'.($x+1).'" value="'.$egysegar.'">			   			   
			  <!-- SEGÉDMEZÕK EDDIG --> 
			  </td>	
			  <td class="vilagos" align="center" id="fogy_havi_c'.$i.'_h'.($x+1).'" bgcolor="#e8e8e8">'.penzFormatum($sumHavi).'<br><font style="font-size:10px">'.$mertekegyseg.'</font></td>
			  <td class="vilagos" align="center" bgcolor="#e8e8e8">'.$egysegar.'<br>Ft</td>
			  <td class="vilagos" align="center" id="ertek_havi_c'.$i.'_h'.($x+1).'" bgcolor="#e8e8e8">'.penzFormatum($ertek).'<br>Ft</td>
			 </tr>';		
		 $ertekEves+=$ertek;
		  
	    } // For hónapok vége	
		
		echo '<tr style="background: #6e9d2f;color:#000;font-weight: bold;">
			  <td class="cimsor" height="40px">Összesen:</td>
			  
			  <!-- ismétlõdõ -->';
			  $kwEves = 0;
			  for($j=1;$j<=$n;$j++) {
			  
			  // Éves fogyasztás számolása
			  // Javítva: 2018.04.15
			  $sql3 =  mysql_query("SELECT MIN(`nyito`) FROM fogyasztas WHERE id_mero='".$oraID[$j]."' AND ev='$ev'"); 
			  //$sql3 =  mysql_query("SELECT MIN(`nyito`) FROM fogyasztas WHERE id_mero='".$oraID[$j]."' AND ev='$ev' AND nyito>'0'");			  
			  $elsoHo = mysql_fetch_array($sql3);			  
			  $sql4 = mysql_query("SELECT MAX(`zaro`) FROM fogyasztas WHERE id_mero='".$oraID[$j]."' AND ev='$ev'");
			  $eves_fogyasztas = mysql_fetch_array($sql4);
			  $sumEviOra = $eves_fogyasztas['MAX(`zaro`)']-$elsoHo['MIN(`nyito`)'];			     if($sumEviOra<0) $sumEviOra=0; 
			  
			  if($j%2!=0) $bg = 'bgcolor="#e8e8e8"'; else $bg='';
			  echo '<td class="cimsor"></td>
			  <td class="cimsor" align="center" id="eviOsszfogy_c'.$i.'_o'.$j.'">'.penzFormatum($sumEviOra).' <font style="font-size:10px">'.$mertekegyseg.'</font></td><input type="hidden" id="rejtett_eviOsszfogy_c'.$i.'_o'.$j.'" value="'.$sumEviOra.'">';
			  $kwEves+=$sumEviOra;
			  }
			  if($kwEves<0) $kwEves=0;
			  
			  if($n<4) {
				  for($i2=$n;$i2<4;$i2++) {
					echo '<td class="cimsor"></td>
					<td class="cimsor" align="center">&nbsp; <font style="font-size:10px">&nbsp;</font></td>';  
				  }
			  }
			  
			  
		echo '<!-- ismétlõdõ eddig -->
		
			  <td id="potCella"></td>	
			  <td class="cimsor" align="center" id="eviFogy_c'.$i.'">'.penzFormatum($kwEves).' <font style="font-size:10px">'.$mertekegyseg.'</font>
			  <input type="hidden" name="evi_fogy_befor_c'.$i.'" value="'.$kwEves.'">
			  </td>
			  <td class="cimsor"></td>
			  <td class="cimsor" align="center" id="ertekEves_c'.$i.'">'.penzFormatum($ertekEves).' Ft</td>
			 </tr>			
			 <!--<tr><td colspan="6" align="right"><input type="button" id="mentesGomb" value="Mentés" style="background:#BDD07F;border:none;width:100px;text-align:center;font-weight:bold;line-height:30px;cursor:pointer"></td></tr>-->
		     </table>';	
		echo '<center>
		<!-- <input type="button" class="gombSzurke" value="EMAILEXPORT" name="EmailExport" onClick="" style="height:40px;margin-right:10px;margin-top:50px;">-->
		
		 <!--<input type="button" class="gombZold" value="NYOMTATÁS" name="Print" onClick="$(\'.cegTabla\').removeClass(\'toPrint\');$(\'#ceg'.$c['id_ceg'].'\').addClass(\'toPrint\');$(\'#ceg'.$c['id_ceg'].'\').removeClass(\'page\');window.print();" style="height:40px;margin-top:50px;">-->
		 
		</center>';
	  
	  
		
		
	  echo '</div>';
	  
	  $i++;
	 } // Ha vége (nincs iylen közmûve a cégnek) 

	}	
}




/*
#######################################################################
				FOGYASTÁS ÖSSZESÍTÕ TÁBLÁZATAI (havi, éves)
#######################################################################
*/

function getOsszesenHavi() {
	
    $kozmu = $_SESSION['kozmu'];	
	$ev = $_SESSION['ev'];
	$telephely = $_SESSION['telephely'];
	
    // Közmû mértékegység lekérdezése
	if(!$sql = mysql_query("SELECT * FROM kozmu WHERE id_kozmu ='$kozmu'")) echo mysql_error();
	$k = mysql_fetch_array($sql);	
	$mertekegyseg = $k['mertekegyseg'];
	
	$ag = $_SESSION['kozmu_nev'];	
	
	$ho = array('Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');
	
	//$sql=  mysql_query("SELECT * FROM ceg WHERE telephely='$telephely' AND cegnev NOT LIKE '%Összesen%' AND cegnev NOT LIKE '%Fõmérõ%' AND aktiv LIKE 'igen' ORDER BY sorrend");
	$sql = mysql_query("SELECT * FROM ceg WHERE telephely='$telephely' AND cegnev NOT LIKE '%Összesen%' AND cegnev NOT LIKE '%Fõmérõ%' ORDER BY sorrend");
	$i=0;
	while($c=mysql_fetch_array($sql))
	{
	
	 $sql1 = mysql_query("SELECT * FROM mero WHERE id_ceg='".$c['id_ceg']."' AND kozmu='$kozmu' AND `nyito_ev`<='$ev' AND (`zaro_ev`='0' OR `zaro_ev`>='$ev') AND aktiv LIKE 'igen'");
	 $n = mysql_num_rows($sql1); // Aktív mérõk száma
	
     if($n>0) {  
	 $ceg[$i] = $c['cegnev'];	// Csak ha van aktív mérõje az adott cégnek az adott közmûre
	 while($m=mysql_fetch_array($sql1)) { // Mérõ	
	 for($j=0;$j<12;$j++) {		
	  $sql2 = mysql_query("SELECT * FROM fogyasztas WHERE id_mero='".$m['id_mero']."' AND ev='$ev' AND ho='".($j+1)."'");
	  while($f=mysql_fetch_array($sql2)) {
	    // Fogyasztás mehatározása cégenként
	    if($f['zaro']>=$f['nyito']) $fogyCegHavi[$i][$j]+=$f['zaro']-$f['nyito'];		
	   }
	  } // for záró 
	 }
	  $i++;
	 }
	}
	
     for($j=0;$j<12;$j++) {
	 $sql3=mysql_query("SELECT * FROM arak WHERE kozmu='$kozmu' AND ev='$ev' AND ho='".($j+1)."'");
	 while($a = mysql_fetch_array($sql3)) {
	  if($a['egysegar']!="") $ar[$j]=$a['egysegar']; 
	  }
	 }

	$most = intval(date('m'));
	
	$print = '';
	
	// HAVI TÁBLÁZATOK
   for($y=0;$y<12;$y++)
   {
	   
	 if($y==($most-1)) $style="display:block"; else $style="display:none";  
	 
	 $print.= '<div id="ho_'.$y.'" style="'.$style.'" class="haviOsszesito"><font id="cimNyomtatni">'.$ev.'. '.strtolower($ho[$y]).' havi '.strtolower($ag).' fogyasztás összesítõ</font><br><br><table border="0" id="kozmuTabla1" cellspacing="0" cellpadding="3px" style="font-size:110%;margin-bottom:5px;"><tr bgcolor="#BDD07F"><td class="cimsor" width="70px" height="50px" style="text-indent:20px;text-align:left;"><b>Cégnév</b></td><td class="cimsor" width="70px" align="center" style="background: #6e9d2f;color:#000;">Összesen:</td><td class="cimsor" width="50px" align="center" style="color:#000;">Egységár:</td><td class="cimsor" width="70px" align="center" style="background: #6e9d2f;color:#000;">Érték:</td></tr>';	
	 $mindenKwh = 0;
	 $mindenErtek= 0;
	 for($x=0;$x<$i;$x++) {	 
      $haviAr = $ar[$y]*$fogyCegHavi[$x][$y];
	  $mindenErtek +=  $haviAr;
	  $mindenKwh += $fogyCegHavi[$x][$y];
	  if(isset($fogyCegHavi[$x][$y])) $haviFogy = $fogyCegHavi[$x][$y]; else $haviFogy=0;
	  $print.= '<tr><td class="vilagos" width="40%" style="text-indent:20px;text-align:left;">'.$ceg[$x].'</td><td class="vilagos" align="center" bgcolor="#e8e8e8">'.penzFormatum($haviFogy).' '.$mertekegyseg.'</td><td align="center" class="vilagos">'.$ar[$y].' Ft</td><td align="center" class="vilagos" bgcolor="#e8e8e8"  id="cella">'.penzFormatum($haviAr).' Ft</td><tr>';
	 }	
	 $e = $ertek; // Összeérték	Ft
	 $print .= '<tr style="background: #6e9d2f;color:#000;font-weight: bold;"><td class="vilagos" width="40%" style="text-indent:20px;text-align:left"><b>Összesen:</b></td><td class="vilagos" align="center">'.penzFormatum($mindenKwh).' '.$mertekegyseg.'</td><td align="center" class="vilagos"></td><td align="center" class="vilagos">'.penzFormatum($mindenErtek).' Ft</td><tr></table>';
		  
	 $print .= '</div>';	
    }
	
	echo $print;
	
	// Nyomtatás gomb
	echo '<script>
			function myFunction()
			{
			 window.print();
			}
		 </script>
		 <!--<center><input type=button class="gombZold" value="'.$ag.' fogyasztás összesítõ NYOMTATÁSA" name="Print" onClick="$(\'.cegTabla\').removeClass(\'toPrint\');$(\'#ceg0\').addClass(\'toPrint\');$(\'#ho_'.$y.'\').removeClass(\'page\');myFunction()" style="width:500px;height:40px;margin-right:10px;margin-top:30px;"></center>-->';		
	
}

function honapok() {
   	$most = intval(date("m"));
	$ho = array('Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');	
	for($i=0;$i<12;$i++) {
		 if($i==($most-1)) $class="aktiv"; else $class="";	
		 if($i==0) echo '<a href="JavaScript:mutatHo(\''.$i.'\')" class="first '.$class.'">'.$ho[$i].'</a>';
		 else if($i==11) echo '<a href="JavaScript:mutatHo(\''.$i.'\')" class="last '.$class.'">'.$ho[$i].'</a>';
		 else echo '<a href="JavaScript:mutatHo(\''.$i.'\')" class="'.$class.'">'.$ho[$i].'</a>';		 
	}
}

function getHoNev() {
	$most = intval(date("m"));
	$ho = array('Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');	
	echo $ho[$most-1];
}


/* ######## ÉVES ÖSSZESÍTÕ ########## */

function getOsszesen() {
	
// Inputok	
    $kozmu = $_SESSION['kozmu'];
	$ev = $_SESSION['ev'];
	$telephely = $_SESSION['telephely'];	
    // Közmû mértékegység lekérdezése
	 if(!$sql = mysql_query("SELECT * FROM kozmu WHERE id_kozmu ='$kozmu'")) echo mysql_error();
	 $k = mysql_fetch_array($sql);	
	$mertekegyseg = $k['mertekegyseg'];
	$ag = $_SESSION['kozmu_nev'];		
	
	$ho = array('Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December');

// Lekérdezés	
   //$sql=  mysql_query("SELECT * FROM ceg WHERE telephely='$telephely' AND cegnev NOT LIKE '%Összesen%' AND cegnev NOT LIKE '%Fõmérõ%' AND aktiv LIKE 'igen' ORDER BY sorrend");
   $sql=  mysql_query("SELECT * FROM ceg WHERE telephely='$telephely' AND cegnev NOT LIKE '%Összesen%' AND cegnev NOT LIKE '%Fõmérõ%' ORDER BY sorrend");
   while($c=mysql_fetch_array($sql))
   {
		
	$sql1 = mysql_query("SELECT * FROM mero WHERE id_ceg='".$c['id_ceg']."' AND kozmu='$kozmu' AND `nyito_ev`<='$ev' AND (`zaro_ev`='0' OR `zaro_ev`>='$ev') AND aktiv LIKE 'igen'");
	$n = mysql_num_rows($sql1); // Csak ha van aktív mérõje az adott cégnek az adott közmûre
	if($n>0)
	{
	 while($m=mysql_fetch_array($sql1)) 
	 {
		$kwEves[$i] = 0;	
		for($i=0;$i<12;$i++)
		{		
			$sql2 = mysql_query("SELECT * FROM fogyasztas WHERE id_mero='".$m['id_mero']."' AND ev='$ev' AND ho='".($i+1)."'");
			while($f=mysql_fetch_array($sql2))
			{	   
				if($f['zaro']>=$f['nyito']) $kwEves[$i]+=$f['zaro']-$f['nyito'];		
			}
		} 
	 }
	}  
   }

	$print = '';
	
	$print.= '<table border="0" id="kozmuTabla1" cellspacing="0" cellpadding="3px" style="font-size:120%;margin-bottom:5px;"><tr bgcolor="#BDD07F"><td class="cimsor" width="70px" height="50px" style="text-indent:20px;text-align:left;"><b>Hónap</b></td><td class="cimsor" width="70px" align="center" style="background: #6e9d2f;color:#000;">Összesen:</td><td class="cimsor" width="50px" align="center" style="color:#000;">Egységár:</td><td class="cimsor" width="70px" align="center" style="background: #6e9d2f;color:#000;">Érték:</td></tr>';
	
	
	for($j=0;$j<12;$j++) {
	 
	 // Árak    
	 $sql3=mysql_query("SELECT * FROM arak WHERE kozmu='$kozmu' AND ev='$ev' AND ho='".($j+1)."'");
	 while($a = mysql_fetch_array($sql3)) {
	  if($a['egysegar']!="") $ar[$j]=$a['egysegar']; 
	  }
	 
	 
	 $kw=0;
	 if(isset($kwEves[$j]))$kw = $kwEves[$j];
	 $haviAr[$j] = $kw*$ar[$j];
	 $ha = penzFormatum($haviAr[$j]);
	 $k = penzFormatum($kw);
	 $print.= '<tr><td class="vilagos" height="35px" width="40%" style="text-indent:20px; text-align:left">'.$ho[$j].'</td><td class="vilagos" align="center" bgcolor="#e8e8e8">'.$k.' '.$mertekegyseg.'</td><td align="center" class="vilagos">'.$ar[$j].' Ft</td><td align="center" class="vilagos" bgcolor="#e8e8e8"  id="cella">'.$ha.' Ft</td><tr>';
	 $kwOssz+=$kw;
	 $ertek+=$haviAr[$j];
	}			
	 
	$print .= '<tr style="background: #6e9d2f;color:#000;font-weight: bold;"><td class="vilagos" width="40%" style="text-indent:20px;text-align:left"><b>Összesen:</b></td><td class="vilagos" align="center">'.penzFormatum($kwOssz).' '.$mertekegyseg.'</td><td align="center" class="vilagos"></td><td align="center" class="vilagos">';
	$print .= penzFormatum($ertek);
	$print .= ' Ft</td><tr></table>';
		
	
	echo $print;
	echo '<script>
function myFunction()
{
window.print();
}
</script>
<!--<center><input type=button class="gombZold" value="'.$_SESSION['ev'].' évi '.strtolower($ag).' fogyasztás összesítõ NYOMTATÁSA" name="Print" onClick="$(\'.cegTabla\').removeClass(\'toPrint\');$(\'#ceg0\').addClass(\'toPrint\');myFunction()" style="width:500px;height:40px;margin-right:10px;margin-top:30px;"></center>-->';		
	
}

/* ######## FOGYASZTÁS RÖGZÍTÉSE ########## */

function set_meroallas() {
	if(isset($_POST['submitFogyasztas'])) {
	 $ev=$_SESSION['ev'];	 
	
	 $sql = mysql_query("SELECT * FROM mero");
	   $ugord_at = 0;  
	   while($m=mysql_fetch_array($sql)) {
	    $i = 1;    
	    while($i<=12) {
		  
		  $zaro = "";		  	 
		  $zar = 'zaro_'.$m['id_mero'].'_'.$i;
		  
		  if($_POST[$zar]!="") {
		   $zaro = $_POST[$zar]; 			
		
		   mysql_query("UPDATE fogyasztas SET zaro='$zaro' WHERE id_mero = '".$m['id_mero']."' AND ev='$ev' AND ho='".$i."'");		 
		  
		  // Van már ilyen sorunk?
			 $sql3 = mysql_query("SELECT * FROM fogyasztas WHERE id_mero = '".$m['id_mero']."' AND ev='$ev' AND ho='".($i+1)."'");
			 $mentve2 = mysql_num_rows($sql3);
			 if($mentve2==0) { // Még nincs ilyen, ezért beszúrjuk
			// INSERT			
			mysql_query("INSERT INTO fogyasztas(id_fogy,id_mero,ev,ho,nyito,zaro) VALUES(
		    NULL,
			'".$m['id_mero']."',
			'$ev',
			'".($i+1)."',
			'$zaro',
			'')");
			
			 } else { // Már van ezért frissítjük
			 mysql_query("UPDATE fogyasztas SET zaro='$zaro' WHERE id_mero = '".$m['id_mero']."' AND ev='$ev' AND ho='".$i."'");			 
			 mysql_query("UPDATE fogyasztas SET nyito='$zaro' WHERE id_mero = '".$m['id_mero']."' AND ev='$ev' AND ho='".($i+1)."'");			 
			 }
		  
		  } // Ha volt post	
		
		  if($_POST[$zaro]=="-") {
		    $zaro = 0; 
		    mysql_query("UPDATE fogyasztas SET zaro='$zaro' WHERE id_mero = '".$m['id_mero']."' AND ev='$ev' AND ho='".$i."'");			
			mysql_query("UPDATE fogyasztas SET nyito='$zaro' WHERE id_mero = '".$m['id_mero']."' AND ev='$ev' AND ho='".($i+1)."'");
		  }	
		  
		 
  		$i++; 
		} // HÓNAPOK 12-ES FOR CIKLUÁNAK VÉGE 
	  } // MÉRÕK WHILE CIKLUSÁNAK VÉGE
	 header('Location:./?p=kezdolap&elmentve=igen');
	}
}
set_meroallas();

function create_startdata($kozmu, $ev) {

 if(isset($_POST['submitGenerateNewYear'])) {
		$sql = mysql_query("SELECT * FROM user WHERE password='".md5($_POST['password'])."'");
		$n = mysql_num_rows($sql);
		if($n>0) {
	
$sql = mysql_query("SELECT * FROM mero WHERE kozmu LIKE '$kozmu' AND aktiv LIKE 'igen'");
 $i = 1;
 while($m = mysql_fetch_array($sql)) {
	$sql2 = mysql_query("SELECT * FROM fogyasztas WHERE id_mero LIKE '".$m['id_mero']."' AND ho LIKE '12' AND ev LIKE '".($ev-1)."'");
	$f = mysql_fetch_array($sql2);
	mysql_query("DELETE FROM fogyasztas WHERE id_mero LIKE '".$m['id_mero']."' AND ho LIKE '1' AND ev LIKE '$ev'");
	if(!mysql_query("INSERT INTO fogyasztas(id_fogy,id_mero,ev,ho,nyito,zaro) VALUES(NULL,'".$m['id_mero']."','$ev','1','".$f['zaro']."','');")) echo mysql_error()."<br>";
	$i++;
 }
 echo '<p align="center" style="color:green;font-weight:bold;">Induló állás beszúrva '.$i.' db mérõórához.</p>';
		} else header('Location:login.php?error=bad_pswd');
 }
 
}
?>