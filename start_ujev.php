<h1>Új év kezdése az elõzõ év záróadataival</h1>

<p>Amennyiben a <?php echo (date('Y'))-1; ?>-es év <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolgáltatás</b> decemberi záróadatait át kívánja vinni a <?php echo date('Y'); ?>-ös év januári nyitóadataiként, kattintson a tovább gombra.</p>
<p>Az adatok exportálása <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolgáltatás</b> aktív mérõinél történik meg. <b style="color:red">Ezt a menüpontot csak akkor használja, ha az elõzõ év decemberi záróadatait már rögzítette a rendszerben!</b></p> 
<p>Amennyiben a többi közmû adatait is szeretné inicializálni, kérjük hogy a kezdõlapon válassza ki a kívánt közmûvet, majd használja ismét ezt a menüpontot.</p>
<a href="?p=start_ujev&save=1" title="Adatbázis exportálása"><input type="button" value="Tovább"></a>
<?php
if($_GET['save']=="1") {
	create_startdata($_SESSION['kozmu'],date('Y'));
}
?>