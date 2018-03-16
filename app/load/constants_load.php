<?php 
/* ---------------------------------------------------
		   Obtenemos las variables de .dataconfig 
	--------------------------------------------------- */
	try {
		$data = \leerDatos();
	} catch (Exception $e) {
		throw $e->getMessage(); 
	}
	/* ---------------------------------------------------
		Obtenemos el host en la cual esta el proyecto
	--------------------------------------------------- */
	$https = (!empty($_SERVER['HTTPS']) ? 'https' : 'http');
	if (stristr($_SERVER["HTTP_HOST"], "localhost") === false) {
		$this -> host = $https . '://' . $_SERVER["HTTP_HOST"] .'/';
	} else {
		$urldata = explode('/', $_SERVER['SCRIPT_NAME']);
		$folder = $urldata[1];
		$public = $urldata[2];
		$this -> host = $https . '://' . $_SERVER["HTTP_HOST"] .'/' . $folder . '/' . $public . '/' ;
	}
	/* ---------------------------------------------------
			Constantes del proyecto
	--------------------------------------------------- */
	DEFINE('DEBUG'    	,	 	$data['DEBUG']); // <-- Dirección Host
	DEFINE('HOST'		, 		$data['HOST']); // <-- Dirección Host
	DEFINE('USER'		, 		$data['USER']);  // <-- Nombre de Usuario 
	DEFINE('PASS'		, 		$data['PASS']); // <-- Contraseña para acceso a la Base de Datos
	DEFINE('DBNAME'		,		$data['DBNAME']); // <-- Nombre de la Base de Datos
	DEFINE('HOME'		,	 	$this -> host); // <-- URL principal
	DEFINE('BASE'		, 		$this -> host); //  <-- Dirección Vistas
	DEFINE('FB_ID'		, 		$data['FB_ID']); // <-- ID FB
	DEFINE('COPY'		,		'Ancaor &trade;'.' 2015 - '.date('Y')); //<-- Copy Right
	DEFINE('DIR_LIBS'	,		'libs/');  // <-- Dirección de archivos HTML
	DEFINE('DIR_LIBS2'	,		'librerias/');
	DEFINE('DIR_BS'		,		'libs/bootstrap/'); // BOOTSTRAP	
	DEFINE('DIR_RS'		,		'./'); // RESOURCES
	DEFINE('IMAGE'		,		'./images/'); // <-- IMAGES
	DEFINE('DATE'		,		date('d-Y-m')); // Fecha Servidor 
	DEFINE('AUTHOR'		,		$data['AUTHOR']); // <-- Autor de la pagina
	DEFINE('WEBSITE'	,		$data['WEBSITE']);