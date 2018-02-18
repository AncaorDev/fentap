<?php namespace app\manager;

use app\clases\Functions;
use app\manager\Loader;
use app\clases\Log;

class Request{
	private $controller;
	private $subcontroller;
	private $metodo;
	private $atributo;
	function __construct(){
		if (isset($_GET['url'])) {
			$lurl = Functions::limpiarCadena($_GET['url']);
			$url = explode('/',$lurl);
			$n_url = count($url);
			if ($n_url>=1) {
				$this->controller = Functions::limpiarURL($url[0]);
				if ($this->controller == 'panel') {
					$this->subcontroller = isset($url[1]) ?  Functions::limpiarURL($url[1]) : '' ;
					$this->metodo = isset($url[2]) ?  Functions::limpiarURL($url[2]) : '' ;
					$this->atributo = isset($url[3]) ? Functions::limpiarURL($url[3]): '';
				} else {
					$this->metodo = isset($url[1]) ?  Functions::limpiarURL($url[1]) : '' ;
					$this->atributo = isset($url[2]) ? Functions::limpiarURL($url[2]): '';
				}
			} else {
				$this->controller = $url[0];
			}
		} else {
			$this->controller = "inicio";
		}
		$this->Procesar();	
	}
	 private function Procesar(){
	  	Loader::filterController($this->controller);
	 }
	
}	