<h1>�j �v kezd�se az el�z� �v z�r�adataival</h1>

<p>Amennyiben a <?php echo (date('Y'))-1; ?>-es �v <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolg�ltat�s</b> decemberi z�r�adatait �t k�v�nja vinni a <?php echo date('Y'); ?>-�s �v janu�ri nyit�adataik�nt, kattintson a tov�bb gombra.</p>
<p>Az adatok export�l�sa <b><?php echo strtolower($_SESSION['kozmu_nev']); ?> szolg�ltat�s</b> akt�v m�r�in�l t�rt�nik meg. <b style="color:red">Ezt a men�pontot csak akkor haszn�lja, ha az el�z� �v decemberi z�r�adatait m�r r�gz�tette a rendszerben!</b></p> 
<p>Amennyiben a t�bbi k�zm� adatait is szeretn� inicializ�lni, k�rj�k hogy a kezd�lapon v�lassza ki a k�v�nt k�zm�vet, majd haszn�lja ism�t ezt a men�pontot.</p>
<a href="?p=start_ujev&save=1" title="Adatb�zis export�l�sa"><input type="button" value="Tov�bb"></a>
<?php
if($_GET['save']=="1") {
	create_startdata($_SESSION['kozmu'],date('Y'));
}
?>