<?php namespace ajax;
/**
* 
*/

use controllers\page\inicioController;

class ajaxInicio extends inicioController 
{
	public function Retorno($datos="") {
		$frase =  parent::obtenerFrase();
		return array('frase' => $frase ,'error' => false );
	}
}

