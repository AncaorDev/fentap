<?php namespace app\clases;
/**
* 
*/
class Functions 
{
	public static function limpiarCadena($url){
	    $url = strtolower($url);
	    //Rememplazamos caracteres especiales latinos
	    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	    $repl = array('a', 'e', 'i', 'o', 'u', 'n');
	      $url = str_replace ($find, $repl, $url);
	    return $url;
	  }
	public static function limpiarURL($url){
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
	    $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/','/\-+/');
	    $repl = array('', '-', '' ,'_');
	    $url = preg_replace($find, $repl, $url); 
	    return $url;
	}
	public static function redirect($destino){
		header('Location:'.HOME.$destino);
	}
}