<div style="float:left;width:48%;margin-right:2%;">
<h1>Adatb�zis ment�se</h1>

<p>Amennyiben az adatb�zisban t�rolt adatokat el k�v�nja menteni, kattintson a tov�bb gombra?</p>


<div class="szuro noprint" style="background-image:url(images/next-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:0px;margin-right:0px;margin-bottom:8px;" title="Adatb�zis export�l�sa">
     <div class="parameter" style="width:90px;text-indent:10px;"><a href="#exportData" rel="prettyPhoto" style="color:#232323;">Tov�bb</a></div>
</div>

 <!-- ### Adatok export�l�sa ### -->	  
	  
		    <div id="exportData" style="display:none;height:160px;float:left;">
			 <form action="?p=export&save=1" method="post">
			  <h1>Adatb�zis ment�se</h1><br>		
			  
			  <p>Biztosan el k�nja menteni az adatb�zis tartalm�t?</p>
			  
			  <p>Amennyiben a "Ment�s" gombra kattint, a rendszer archiv�lni fogja az eddig r�gz�tett adatokat.</p>
			  
			  <div align="right"><table class="prettyTable" style="margin-right:-1px;"><tr><td>Jelsz�:&nbsp;</td><td><input type="password" name="password" required class="inputPretty"></td></tr></table></div>
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
	echo '<p align="center"><a href="'.$_GET['file'].'" download="download">A ment�s sikeresen befejez�d�tt. Az elk�sz�lt <b>Backup file</b> let�lthet� az erre a linkre kattintva.</a></p>';
}
?>

<h3>A szerveren jelenleg el�rhet� ment�si �llom�nyok:</h3>
<?php 
// List�z�s
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
 if($i==0) echo '<p align="center">Nincs felt�lt�tt f�jl.</p>';
 else {
  natsort($files); // ABC sorrendbe rendez�s
  $files = array_reverse($files, false); // Visszafel� rendez�s, kezdve a legnagyobbal
  $j = 0;
  foreach($files as $file) {
     echo('<p class="fileRow"><a href="save_database/'.$file.'" download="'.$file.'">'.$file.'</a> &nbsp;<a href="#deleteFile_'.$j.'" rel="prettyPhoto"><span class="deleteFileBtn" title="T�rl�s">X</span></a></p>');
	 
	 echo '<div id="deleteFile_'.$j.'" style="display:none;height:140px;float:left;">
		<form action="" method="post">
		<h1>F�jl t�rl�se</h1>		
		<br>
		<p>Biztosan t�rli ezt a f�jlt: <span><b>'.$file.'</b></span>?</p>
		<p>Amennyiben a t�rl�s gombra kattint, a f�jl t�rl�sre ker�l.</p>		
		<div align="right"><table class="prettyTable"><tr><td>Jelsz�:&nbsp;</td><td><input type="password" name="password" class="inputPretty"> <input type="hidden" name="id_telephely" value="'.$t['id_telephely'].'"> </td></tr></table></div>
		<input type="submit" name="submitDeleteFile" class="deleteBtnPretty" value="">		
		</form>
	  </div>';
	  
	  $j++;
  } 
  
 }
?>
</div>
<div style="float:left;width:48%;">
<h1>�j �v kezd�se az el�z� �v z�r�adataival</h1>

<p>Amennyiben az <b>aktu�lis</b> �v <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolg�ltat�s</b> decemberi z�r�adatait �t k�v�nja vinni a <b>k�vetkez�</b> �v janu�ri nyit�adataik�nt, kattintson a tov�bb gombra.</p>
<p>Az adatok export�l�sa <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolg�ltat�s</b> akt�v m�r�in�l t�rt�nik meg. <b style="color:red">Ezt a men�pontot csak akkor haszn�lja, ha az el�z� �v decemberi z�r�adatait m�r r�gz�tette a rendszerben!</b></p> 
<p>Amennyiben a t�bbi k�zm� adatait is szeretn� inicializ�lni, k�rj�k hogy a <b>Fogyaszt�s</b> men�pontban v�lassza ki a k�v�nt k�zm�vet, majd haszn�lja ism�t ezt a men�pontot.</p>
<div class="szuro noprint" style="background-image:url(images/next-arrow.png);background-size:auto 55%;background-position:5px center;float:right;margin-top:0px;margin-right:0px;margin-bottom:8px;" title="�vnyit�s">
     <div class="parameter" style="width:90px;text-indent:10px;"><a href="#ujEv" rel="prettyPhoto" style="color:#232323;">Tov�bb</a></div>
</div>

 <!-- ### �j �v nyit�sa? ### -->	  
	  
		    <div id="ujEv" style="display:none;height:160px;float:left;">
			 <form action="?p=export&nyit=1" method="post">
			  <h1>�j �v kezd�se az el�z� �v z�r�adataival</h1><br>		
			  
			  <p>Biztosan elind�tja az �j �v l�trhoz�s�t a megl�v� adatok felhaszn�l�s�val?</p>
			  
			  <p>Amennyiben a "Ment�s" gombra kattint, a rendszer az eddig r�gz�tett z�r� adatokb�l l�tre fogja hozni a k�vetkez� �v nyit�adatait.</p>
			  
			  <div align="right"><table class="prettyTable" style="margin-right:-1px;"><tr><td>Jelsz�:&nbsp;</td><td><input type="password" name="password" required class="inputPretty"></td></tr></table></div>
			  <input type="submit" name="submitGenerateNewYear" value="" class="saveBtnPretty">
			 </form>
			</div>

<?php
if($_GET['nyit']=="1") {
	create_startdata($_SESSION['kozmu'],date('Y'));
}
?>
</div>