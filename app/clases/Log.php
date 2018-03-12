<?php namespace app\clases;
/**
* Mejorar
*/
class Log 
{
	static function _log($var) {
		$dbgt = debug_backtrace();
        if(isset($dbgt[1]['class'])) {
            $class = $dbgt[1]['class'];
        } else {
            $ruta = explode(DIRECTORY_SEPARATOR, $dbgt[0]['file']);
            $class = end($ruta);
        }
        self::error('( '.$class.' -> '.$dbgt[1]['function'].') (linea: '.$dbgt[0]['line'].') >> '.$var, 'error');
	}

	static function error($texto,$numero = 'Error :: ') { 
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