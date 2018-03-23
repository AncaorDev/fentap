<?php namespace app\manager;

class Loader 
{
	private $data;
	static function filterController($data,$view = true) {
		self::host();
		$cant = count($data);
	    if ($cant > 0) {
	    	if ($data['controller'] == 'panel' && $data['subcontroller'] != '') {
	    		self::runSubController($data,$view);
	    	} else {
	    		self::runController($data,$view);
	    	}
	      
	    } 	   
	}

	static function runController($data,$view = true) {
		// Si es mayor de 0 comprabamos que exista en la carpeta page.
      	if (file_exists(__DIR__.'/../../controllers/page/'.$data['controller'].'Controller.php')) {
	        // Si el archivo existe lo requerimos.
	        require_once(__DIR__.'/../../controllers/page/'.$data['controller'].'Controller.php');
	        // Asignamos nuestro controlador. 
        	$controller = 'controllers\page\\'.$data['controller'].'Controller';  
      	} else if ($data['controller'] == 'index' || $data['controller'] == 'index.php'){
        	\redirect('inicio');
      	}else {
        	require(__DIR__.'/../../controllers/error/error404Controller.php');
        	$controller = 'controllers\error\error404Controller';  
        	\__log('error');
      	}  
		$vista = new $controller($data);
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $data['metodo'] != '') {
			$metodo = $data['metodo'];
			\__log('Ejecutando metodo : ' . $metodo);
			$vista -> $metodo();
		} else {
			if ($view) {
	    		$vista -> index(); 
		    } else {
		    	return $vista;
		    }
		}
	}

	static function runSubController($data,$view = true) {
		// Si es mayor de 0 comprabamos que exista en la carpeta page.
      	if (file_exists(__DIR__.'/../../controllers/panel/'.$data['subcontroller'].'Controller.php')) {
	        // Si el archivo existe lo requerimos.
	        require_once(__DIR__.'/../../controllers/panel/'.$data['subcontroller'].'Controller.php');
	        // Asignamos nuestro controlador. 
        	$controller = 'controllers\panel\\'.$data['subcontroller'].'Controller';  
      	} else if ($data['subcontroller'] == 'index' || $data['subcontroller'] == 'index.php'){
        	\redirect('inicio');
      	}else {
        	require(__DIR__.'/../../controllers/error/error404Controller.php');
        	$controller = 'controllers\error\error404Controller';  
      	}  
      	unset($data['subcontroller']);
      	$vista = new $controller($data);
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $data['metodo'] != '' && $data['metodo'] != '') {
			\__log('Ejecutando metodo : ' . $data['metodo']);
			$vista -> $data['metodo']();
		} else {
			if ($view) {
	    		$vista -> index(); 
		    } else {
		    	return $vista;
		    }
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