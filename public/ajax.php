<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    if (isset($_GET['mode']) ? $_GET['mode'] : null) {
    	$m = $_GET['mode'];
    	require(__DIR__.'/../ajax/ajaxManager.php');
    	$ajax = new ajax\ajaxManager();    	
		$retorno = $ajax -> ejecutar('ajax'.ucfirst($m));
    	echo json_encode($retorno);
    } 
} else {
	$data = [];
	$data['error'] = "No encentra POST";
    echo json_encode($data);
}

