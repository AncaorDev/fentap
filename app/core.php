<?php namespace app;
/**
* 
*/
class Core {
private $host;
private $url;
function __construct() {
	$this -> ejecutar();
}
function ejecutar(){
	error_reporting(0);
	date_default_timezone_set("America/Lima");
	/* ---------------------------------------------------
	Autoregistro de los modelos
	--------------------------------------------------- */
	spl_autoload_register( function( $NombreClase ) {
		$NombreClase = str_replace('\\', '/' , $NombreClase);
		if (file_exists(__DIR__.'/../model/' . $NombreClase . '.php')) {
			require_once __DIR__.'/../model/' . $NombreClase . '.php';
		}  else if (file_exists(realpath(__DIR__.'/../'.$NombreClase.'.php'))){
			include_once realpath(__DIR__.'/../'.$NombreClase.'.php');
		} else {
			echo "Not found class archive $NombreClase " . " Error en core.php línea : " . __LINE__ . "</br>";
		}
	});
	/* ---------------------------------------------------
		   Obtenemos las variables de .dataconfig 
	--------------------------------------------------- */
	try {
		$data = clases\readFiles::leerDatos();
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
	DEFINE('DEBUG',	 	$data['DEBUG']); // <-- Dirección Host
	DEFINE('HOST', 		$data['HOST']); // <-- Dirección Host
	DEFINE('USER', 		$data['USER']);  // <-- Nombre de Usuario 
	DEFINE('PASS', 		$data['PASS']); // <-- Contraseña para acceso a la Base de Datos
	DEFINE('DBNAME',	$data['DBNAME']); // <-- Nombre de la Base de Datos
	DEFINE('HOME', 		$this -> host); // <-- URL principal
	DEFINE('BASE', 		$this -> host); //  <-- Dirección Vistas
	DEFINE('FB_ID', 	$data['FB_ID']); // <-- ID FB

	DEFINE('COPY','Ancaor &trade;'.' 2015 - '.date('Y')); //<-- Copy Right
	DEFINE('DIR_LIBS','libs/');  // <-- Dirección de archivos HTML
	DEFINE('DIR_LIBS2','librerias/');
	DEFINE('DIR_BS','libs/bootstrap/'); // BOOTSTRAP	
	DEFINE('DIR_RS','./'); // RESOURCES
	DEFINE('IMAGE','./images/'); // <-- IMAGES
	DEFINE('DATE',date('d-Y-m')); // Fecha Servidor 
	DEFINE('AUTHOR',$data['AUTHOR']); // <-- Autor de la pagina
	DEFINE('WEBSITE',$data['WEBSITE']);

	/* ---------------------------------------------------
		Enviamos los datos para Javascript
	--------------------------------------------------- */
	$datajs = ["URL" => $this -> url ];
	// escribirDatos($datajs);
	//Extensión .html o .htm
	// DEFINE('EXT','phtml');
	/* ---------------------------------------------------
		Archivos necesario
	--------------------------------------------------- */

	if (class_exists('app\clases\Session')) {
		clases\Session::init();
		DEFINE('SESSION', clases\Session::exists());
	} else {
		echo "Error en la clase Sesion\rPor favor verificar";
	}
	// var_dump(debug_backtrace());
	if (DEBUG) {
		error_reporting(-1);	
	}
}

public function __destruct(){
	
}

}
//Fin Clase
// echo __FILE__;