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
        if (is_array($var)) {
        	$var = print_r($var,true);
        }
        error('( '.$class.' -> '.$dbgt[1]['function'].') (linea: '.$dbgt[0]['line'].') >> '.$var, 'error');
	}
}

if (!function_exists('error')) {
	function error($texto,$numero = 'Error :: ') { 
		try {
			$path = __DIR__.'/../log/error-'.date('Y-m-d').'.log'; 
			$ddf  = fopen($path,'a+'); 
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
 






