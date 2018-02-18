<?php namespace app\clases;
/**
* 
*/
class readFiles
{
	static function leerDatos() {
	//Leemos el archivo JSON
	$datajson = file_get_contents(__DIR__."/../../config/.dataconfig");

	// Decodificamos los datos
	$infohost = json_decode($datajson);

	// Asignamos a una array 
	return $arr = get_object_vars($infohost);	
	} 
}