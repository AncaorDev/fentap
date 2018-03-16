<?php namespace app\manager;

use app\manager\Loader;

class Request{
	private $request;

	function __construct(){
		$controller    = null;
		$subcontroller = null;
		$metodo        = null;
		$atributo      = null;

		if (isset($_GET['url'])) {
			$lurl       = \limpiarCadena($_GET['url']);
			$url        = explode('/',$lurl);
			$controller = \limpiarURL($url[0]);
			$n_url      = count($url);
			if ($n_url>=1) {
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				   if ($n_url == 2) {
						$subcontroller = '' ;
						$metodo        = isset($url[1]) ? \limpiarURL($url[1],false) : '' ;
					} else if ($n_url == 3) {
						$subcontroller = isset($url[1]) ? \limpiarURL($url[1]) : '' ;
						$metodo   	   = isset($url[2]) ? \limpiarURL($url[2],false) : '' ;
					}
				} else {
					if ($controller == 'panel') {
						$subcontroller = isset($url[1]) ? \limpiarURL($url[1]) : '' ;
						$metodo        = isset($url[2]) ? \limpiarURL($url[2],false) : '' ;
						$atributo      = isset($url[3]) ? \limpiarAttr($url[3]) : '';
					} else {
						$metodo   = isset($url[1])   ? \limpiarURL($url[1]) : '' ;
						$atributo = isset($url[2])   ?\limpiarAttr($url[2]): '';
					}
				}
				$this->request = array( 'controller'    => $controller,
   										'subcontroller' => $subcontroller,
   										'metodo'        => $metodo,
   										'atributo'      => $atributo);
			} else {
				$this->request = array('controller' => $controller);
			}
		} else {
			$this->request = array('controller' => 'inicio');
		}
		$this->Procesar();	
	}
	 private function Procesar(){
	 	\__log(print_r($this->request,true));
	  	Loader::filterController($this->request);
	 }
	
}	