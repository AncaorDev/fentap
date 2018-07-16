<?php namespace app;
/**
*
*/
class Core {
	private $host;
	private $url;
	function __construct() {
		$this -> ejecutar();
		date_default_timezone_set("America/Lima");
	}
	function ejecutar(){
		error_reporting(0);
		$this -> load('functions,constants');
		/* ---------------------------------------------------
		Autoregistro de los modelos
		--------------------------------------------------- */
		spl_autoload_register( function( $NombreClase ) {
			if($NombreClase != "finfo") {
				$NombreClase = str_replace('\\', '/' , $NombreClase);
				if (file_exists(__DIR__.'/../model/' . $NombreClase . '.php')) {
					require __DIR__.'/../model/' . $NombreClase . '.php';
				}  else if (file_exists(realpath(__DIR__.'/../'.$NombreClase.'.php'))){
					require realpath(__DIR__.'/../'.$NombreClase.'.php');
				} else {
					echo "Not found class archive $NombreClase " . " Error en core.php línea : " . __LINE__ . "</br>";
				}
			}
		});
		/* ---------------------------------------------------
			Archivos necesarios
		--------------------------------------------------- */
		if (class_exists('app\clases\Session')) {
			clases\Session::init();
			DEFINE('SESSION', clases\Session::exists());
		} else {
			echo "Error en la clase Sesión\rPor favor verificar";
		}
		// var_dump(debug_backtrace());
		if (DEBUG) {
			error_reporting(-1);
		}
	}

	function load($load){
		$load = explode(',', $load);
		if (count($load) > 1) {
			foreach ($load as $key) {
				include(realpath(__DIR__.'/load/'.$key.'_load.php'));
			}
		} else {
			include(realpath(__DIR__.'/load/'.$load[0].'_load.php'));
		}
	}

	public function __destruct(){}
}
