<style type="text/css" media="print">
	.top, .menu, #cegKezeloIkonok, .ujMeroGomb, .actions img, .noprint, .footer {
		display: none;
	}

	#cegAdatlap td {
	 padding: 5px;
	}

	.jobbra {
		margin-right: 0px !important;
		margin-top: 20px;
	}

	.jobbra div {
		width: 170px !important;
	}
	
	h1 {
		margin-bottom: 50px;
	}
</style>
<script>
	$("document").ready(function() {
		$('tbody tr:even').addClass("alt-row");
	});
</script>
<?php
loginControll(); 
getCeg();
?>