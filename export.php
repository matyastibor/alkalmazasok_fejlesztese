<div style="float:left;width:48%;margin-right:2%;">
<h1>Adatbázis mentése</h1>

<p>Amennyiben az adatbázisban tárolt adatokat el kívánja menteni, kattintson a tovább gombra?</p>


<div class="szuro noprint" style="background-image:url(images/next-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:0px;margin-right:0px;margin-bottom:8px;" title="Adatbázis exportálása">
     <div class="parameter" style="width:90px;text-indent:10px;"><a href="#exportData" rel="prettyPhoto" style="color:#232323;">Tovább</a></div>
</div>

 <!-- ### Adatok exportálása ### -->	  
	  
		    <div id="exportData" style="display:none;height:160px;float:left;">
			 <form action="?p=export&save=1" method="post">
			  <h1>Adatbázis mentése</h1><br>		
			  
			  <p>Biztosan el kínja menteni az adatbázis tartalmát?</p>
			  
			  <p>Amennyiben a "Mentés" gombra kattint, a rendszer archiválni fogja az eddig rögzített adatokat.</p>
			  
			  <div align="right"><table class="prettyTable" style="margin-right:-1px;"><tr><td>Jelszó:&nbsp;</td><td><input type="password" name="password" required class="inputPretty"></td></tr></table></div>
			  <input type="submit" name="submitExportDatabase" value="" class="saveBtnPretty">
			 </form>
			</div>
	
<div class="clear"></div>	

<?php
loginControll();
if($_GET['save']=="1") {
backup_tables('localhost','root','root','kozmukezelo');
}

if(isset($_GET['file'])) {
	echo '<p align="center"><a href="'.$_GET['file'].'" download="download">A mentés sikeresen befejezõdõtt. Az elkészült <b>Backup file</b> letölthetõ az erre a linkre kattintva.</a></p>';
}
?>

<h3>A szerveren jelenleg elérhetõ mentési állományok:</h3>
<?php 
// Listázás
$kvt = "save_database/";
 if($k_azon = opendir($kvt)) {
  $i=0;
  while (false !== ($fajl = readdir($k_azon))) {
    if ($fajl != "." && $fajl != "..") {
      $files[] = $fajl;
	  //echo '<p><a href="save_database/'.$fajl.'">'.$fajl.'</a></p>';
      $i++;
	}
  }
  closedir($k_azon);  
  }
 if($i==0) echo '<p align="center">Nincs feltöltött fájl.</p>';
 else {
  natsort($files); // ABC sorrendbe rendezés
  $files = array_reverse($files, false); // Visszafelé rendezés, kezdve a legnagyobbal
  $j = 0;
  foreach($files as $file) {
     echo('<p class="fileRow"><a href="save_database/'.$file.'" download="'.$file.'">'.$file.'</a> &nbsp;<a href="#deleteFile_'.$j.'" rel="prettyPhoto"><span class="deleteFileBtn" title="Törlés">X</span></a></p>');
	 
	 echo '<div id="deleteFile_'.$j.'" style="display:none;height:140px;float:left;">
		<form action="" method="post">
		<h1>Fájl törlése</h1>		
		<br>
		<p>Biztosan törli ezt a fájlt: <span><b>'.$file.'</b></span>?</p>
		<p>Amennyiben a törlés gombra kattint, a fájl törlésre kerül.</p>		
		<div align="right"><table class="prettyTable"><tr><td>Jelszó:&nbsp;</td><td><input type="password" name="password" class="inputPretty"> <input type="hidden" name="id_telephely" value="'.$t['id_telephely'].'"> </td></tr></table></div>
		<input type="submit" name="submitDeleteFile" class="deleteBtnPretty" value="">		
		</form>
	  </div>';
	  
	  $j++;
  } 
  
 }
?>
</div>
<div style="float:left;width:48%;">
<h1>Új év kezdése az elõzõ év záróadataival</h1>

<p>Amennyiben az <b>aktuális</b> év <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolgáltatás</b> decemberi záróadatait át kívánja vinni a <b>következõ</b> év januári nyitóadataiként, kattintson a tovább gombra.</p>
<p>Az adatok exportálása <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolgáltatás</b> aktív mérõinél történik meg. <b style="color:red">Ezt a menüpontot csak akkor használja, ha az elõzõ év decemberi záróadatait már rögzítette a rendszerben!</b></p> 
<p>Amennyiben a többi közmû adatait is szeretné inicializálni, kérjük hogy a <b>Fogyasztás</b> menüpontban válassza ki a kívánt közmûvet, majd használja ismét ezt a menüpontot.</p>
<div class="szuro noprint" style="background-image:url(images/next-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:0px;margin-right:0px;margin-bottom:8px;" title="Évnyitás">
     <div class="parameter" style="width:90px;text-indent:10px;"><a href="#ujEv" rel="prettyPhoto" style="color:#232323;">Tovább</a></div>
</div>

 <!-- ### Új év nyitása? ### -->	  
	  
		    <div id="ujEv" style="display:none;height:160px;float:left;">
			 <form action="?p=export&nyit=1" method="post">
			  <h1>Új év kezdése az elõzõ év záróadataival</h1><br>		
			  
			  <p>Biztosan elindítja az új év létrhozását a meglévõ adatok felhasználásával?</p>
			  
			  <p>Amennyiben a "Mentés" gombra kattint, a rendszer az eddig rögzített záró adatokból létre fogja hozni a következõ év nyitóadatait.</p>
			  
			  <div align="right"><table class="prettyTable" style="margin-right:-1px;"><tr><td>Jelszó:&nbsp;</td><td><input type="password" name="password" required class="inputPretty"></td></tr></table></div>
			  <input type="submit" name="submitGenerateNewYear" value="" class="saveBtnPretty">
			 </form>
			</div>

<?php
if($_GET['nyit']=="1") {
	create_startdata($_SESSION['kozmu'],date('Y'));
}
?>
</div>