<?php 

if (!function_exists('__log')) {
	function __log($var) { 
		$dbgt = debug_backtrace();
        if(isset($dbgt[1]['class'])) {
            $class = $dbgt[1]['class'];
        } else {
            $ruta = explode(DIRECTORY_SEPARATOR, $dbgt[0]['file']);
            $class = end($ruta);
        }
        if (is_array($var) || is_object($var)) {
        	$var = print_r($var,true);
        }
        error('( '.$class.' -> '.$dbgt[1]['function'].') (linea: '.$dbgt[0]['line'].') >> '.$var, 'error');
	}
}

if (!function_exists('error')) {
	function error($texto,$numero = 'Error :: ') { 
		try {
			$folder   = __DIR__.'/../log/';
			$filename = 'error-'.date('Y-m-d').'.log';
			$path     =  $folder.$filename; 
			if (file_exists($path)) {
				$ddf  = fopen($path,'a+'); 
			} else {
				mkdir($folder, 0777, true);
				$ddf  = fopen($path,'w+'); 
			}
			$data = '['.date('Y-m-d H:m:s').']'."$numero - $texto\r\n";
			if (!fwrite($ddf,$data)) {
				throw new \Exception("No se creo archivo Log");	
			} 
			fclose($ddf); 
		} catch (\Exception $e) {
			echo  $e->getMessage();
		}
	} 
}

if (!function_exists('encriptar')) {
	// Encriptación y desencriptación de Contraseña
	function encriptar($cadena){
		$llave = "lidia";
		$len = base64_encode(strlen($cadena));
		$tamanio = strlen($llave);
		$key = substr(md5($llave),0,$tamanio); // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    return $encrypted."+/0LPdc1>".$len; //Devuelve el string encriptado
	 
	}
}

if (!function_exists('desencriptar')) {
	// Encriptación y desencriptación de Contraseña
	function desencriptar($cadena){
		$llave = "lidia";
		// $num = strstr($cadena,"<>");
		$cadena = strstr($cadena,"+/0LPdc1>",true);
		$tamanio = strlen($llave);
		$key = substr(md5($llave),0,$tamanio); // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	    return $decrypted;  //Devuelve el string desencriptado
	}
}

if (!function_exists('generateRandomString')) {
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
    	return $randomString;
	}
}

if (!function_exists('encode_HTML')) {
	function encode_HTML($string) {
	    $html = htmlspecialchars($string, ENT_QUOTES);
    	return $html;
	}
}

if (!function_exists('decode_HTML')) {
	function decode_HTML($string) {
	    $html = htmlspecialchars_decode($string);
    	return $html;
	}
}

if (!function_exists('leerDatos')) {
	function leerDatos() {
	    $datajson = file_get_contents(__DIR__."../../config/.dataconfig");
		// Decodificamos los datos
		$infohost = json_decode($datajson);
		// Asignamos a una array 
		return $arr = get_object_vars($infohost);	
	}
}

if (!function_exists('limpiarCadena')) {
	function limpiarCadena($url){
	    $url = strtolower($url);
	    //Rememplazamos caracteres especiales latinos
	    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	    $repl = array('a', 'e', 'i', 'o', 'u', 'n');
	      $url = str_replace ($find, $repl, $url);
	    return $url;
	}
}

if (!function_exists('limpiarURL')) {
	function limpiarURL($url,$lower = true){
		if ($lower) {
			// Tranformamos todo a minusculas
	  		$url = strtolower($url);
		}
	  	//Rememplazamos caracteres especiales latinos
	    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	    $repl = array('a', 'e', 'i', 'o', 'u', 'n');
	    $url = str_replace ($find, $repl, $url);
	    // Añadimos los guiones
	    $find = array(' ', '&', '\r\n', '\n', '+');
	    $url = str_replace ($find, '-', $url);
	    // Eliminamos y Reemplazamos otros carácteres especiales
	    $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/','/\-+/');
	    $repl = array('', '-', '' ,'_');
	    $url = preg_replace($find, $repl, $url); 
	    return $url;
	}
}

if (!function_exists('limpiarAttr')) {
	function limpiarAttr($url){
	  	// Tranformamos todo a minusculas
	  	$url = strtolower($url);
	  	//Rememplazamos caracteres especiales latinos
	    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	    $repl = array('a', 'e', 'i', 'o', 'u', 'n');
	    $url = str_replace ($find, $repl, $url);
	    // Añadimos los guiones
	    $find = array(' ', '&', '\r\n', '\n', '+');
	    $url = str_replace ($find, '-', $url);
	    // Eliminamos y Reemplazamos otros carácteres especiales
	    $find = array('/[^a-z0-9\_<>]/', '/[\-]+/', '/<[^>]*>/','/\-+/');
	    $repl = array('', '-', '' ,'_');
	    $url = preg_replace($find, $repl, $url); 
	    return $url;
	}
}



if (!function_exists('redirect')) {
	function redirect($destino){
		header('Location:'.HOME.$destino);
	}
}

 

	


