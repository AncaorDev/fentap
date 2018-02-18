<?php 
class crudHTML{

	function crearHtml($id){
		$ruta = "public/views/page/data/data-".$id.".phtml";
		$rutaabierta = fopen($ruta, "w+");
		if ($rutaabierta) {		
			if (fwrite($rutaabierta , "<p>Cambie el contenido del proyecto a mostrar</p>")) {
				return $response = ['msg' => 'CREADO Y AGREGADO' , 'std' => true ];
			} else {
				return $response = ['msg' => 'ERROR AL AGREGAR INFORMACIÓN' , 'std' => false ];
			}
		} else {
			return $response = ['msg' => 'ERROR AL CREAR HTML' , 'std' => false ];
		}
	}
	function editarHtml($id,$code){
		$ruta = "public/views/page/data/data-".$id.".phtml";
		$rutaabierta = fopen($ruta, "w+");
		if (file_exists($ruta)) {
			if (fwrite($rutaabierta , $code)) {
				return $response = ['msg' => 'INFORMACIÓN MODIFICADA' , 'std' => true ];
			} else {
				return $response = ['msg' => 'ERROR AL MODIFICAR INFORMACIÓN' , 'std' => false ];
			}
		} else {
			return $response = ['msg' => 'ERROR'.$id , 'std' => false ];
		}
	}

	function comprobarHtml($id){
		$ruta = "public/views/page/data/data-".$id.".phtml";
		if (file_exists($ruta)) {
			return $response = ['msg' => 'OK' , 'std' => true ];
		} else {
			return $this->crearHtml($id);
		}
	}
	
	function eliminarHtml($id){
		$ruta = "public/views/page/data/data-".$id.".phtml";
		if (file_exists($ruta)) {
			unlink($ruta);
			if ($ruta) {
				return $response = ['msg' => 'ELIMINADO CORRECTAMENTE' , 'std' => true ];
			} else {
				return $response = ['msg' => 'ERROR AL ELIMINAR' , 'std' => false ];
			}		
		} else {
			return $response = ['msg' => 'NO EXISTE' , 'std' => true ];
		}
	}

}