<?php namespace app\manager;

use app\clases\Functions;
use app\clases\Log;

class Loader 
{
	private $data;
	public static function filterController($data,$view = true) {
		$cant = strlen($data);
	    if ($cant > 0) {
	      // Si es mayor de 0 comprabamos que exista en la carpeta page.
	      if (file_exists(__DIR__.'/../../controllers/page/'.$data.'Controller.php')) {
	        // Si el archivo existe lo requerimos.
	        require_once(__DIR__.'/../../controllers/page/'.$data.'Controller.php');
	        // Asignamos nuestro controlador. 
	        $controller = 'controllers\page\\'.$data.'Controller';  
	      } else if ($data == 'index' or $data == 'index.php'){
	        Functions::redirect('inicio');
	      }else {
	        require(__DIR__.'/../../controllers/error/error404Controller.php');
	        $controller = 'controllers\error\error404Controller';  
	      }  
	    } 
	    self::host();
	    $vista = new $controller();
	    if ($view) {
	    	$vista -> index(); 
	    } else {
	    	return $vista;
	    }
	}

	private static function host(){
	    // Obtenemos el Servidor , en caso de server local sera => http://localhost 
	    $host= $_SERVER["HTTP_HOST"];
	    // Obentenos la URL, ejemplo al ser inicio sera => /ancaor2017/inicio
	   	$url= $_SERVER["REQUEST_URI"];
	    //Puede comprobarse descomentando la siguiente linea.
	    // echo 'http:// - HOST => ' . $host . ' - URL => ' . $url . '</br>';
	  }
}