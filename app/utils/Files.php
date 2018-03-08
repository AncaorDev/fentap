<?php namespace app\utils;

class Files{

	function __construct(){

	}
	  /**
	 * Devuelve true o false si creo el directorio
	 * @access public
	 * @param string $path directorio donde se crear el archivo
	 * @param string $name nombre del archivo a crearse
	 * @param string $content contenido del archivo a crearse
	 * @return $result
	 * @author Ancaor
	 */
	static function createFile($path, $name_file = null , $content = null, $over = false){
		$data['error'] 		 = 1;
		$data['msj']         = 'ERROR';
		$data['ruta_fisica'] = null;
		try {
			$path_file = realpath(__DIR__.'/../../'.$path);
			$dir       = "{$path_file}/{$name_file}";
			if (file_exists($dir) && $over == false) {
				$data['msj']	= 'Archivo ya existe, activa la opción de sobreescribir';
			} else {
				\__log('creando');
				if ($name_file == null) {
					$random    = \generateRandomString();
					$name_file = md5(base64_encode($random));
				}
				$files = fopen($dir, 'w+');

				if (fwrite($files, $content)) {
					$data['error']       = 0;
					$data['msj']         = 'Archivo creado con éxito';
					$data['ruta_fisica'] =  $path_file;
				};
				fclose($files);
			}

		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
	 	return $data;
	}
	 
	 /**
	 * Devuelve true o false si se eliminó el directorio
	 * @access public
	 * @param string $path ruta de archivo a eliminar
	 * @return $result
	 * @author Ancaor
	 */
	static function deleteFile($path){
		$data['error'] 		 = 1;
		$data['msj']         = 'ERROR';
		try {
			$path_file = realpath(__DIR__.'/../../'.$path);
			$data['msj']	= 'Archivo no existe';
			if (file_exists($path_file)) {
				$data['msj']	= 'Error al eliminar';
				if (unlink($path_file)) {
					$data['msj']	= 'Archivo eliminado';
					$data['error']	= 0;
				}
			}
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
	 	return $data;
	}
}	
// directorio actual 
// return $dir. "\n";
	// return getcwd();