<?php namespace app\manager;

use app\clases\Functions;
use app\manager\Loader;
use app\clases\Log;

class Request{
	private $request;

	function __construct(){
		$controller    = null;
		$subcontroller = null;
		$metodo        = null;
		$atributo      = null;

		if (isset($_GET['url'])) {
			$lurl       = Functions::limpiarCadena($_GET['url']);
			$url        = explode('/',$lurl);
			$controller = Functions::limpiarURL($url[0]);
			$n_url      = count($url);
			Log::_log($lurl);
			Log::_log($n_url);
			if ($n_url>=1) {
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				   if ($n_url == 2) {
						$subcontroller = '' ;
						$metodo        = isset($url[1]) ?  Functions::limpiarURL($url[1]) : '' ;
					} else if ($n_url == 3) {
						$subcontroller = isset($url[1]) ?  Functions::limpiarURL($url[1]) : '' ;
						$metodo        = isset($url[2]) ?  Functions::limpiarURL($url[2]) : '' ;
					}
				} else {
					if ($controller == 'panel') {
						$subcontroller = isset($url[1]) ?  Functions::limpiarURL($url[1]) : '' ;
						$metodo        = isset($url[2]) ?  Functions::limpiarURL($url[2]) : '' ;
						$atributo      = isset($url[3]) ?  Functions::limpiarURL($url[3]) : '';
					} else {
						$metodo   = isset($url[1])   ?  Functions::limpiarURL($url[1]) : '' ;
						$atributo = isset($url[2])   ? Functions::limpiarURL($url[2]): '';
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
	 	Log::_log(print_r($this->request,true));
	  	Loader::filterController($this->request);
	 }
	
}	