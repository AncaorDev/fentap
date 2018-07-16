<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use app\clases\Log;
use model\Model;

class linksModel extends Model{
	private $table;

	public function __construct() {
		parent::__construct();
		$this -> table = 'notice';
		$this -> con = new gestionBD();
	}

	public function statusTable(){
		try {
			$sql = "SHOW TABLE STATUS LIKE '{$this -> table}'";
			return  $lista = $this -> con -> ejecutararray($sql);
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function setAutoincrement($num){
		try {
			$sql = "ALTER TABLE {$this -> table} AUTO_INCREMENT =".$num;
			$rows = $this -> con -> ejecutar($sql);
			if ($rows) {
				$rows = true;
			} else {
				$rows = false;
			}
			$data = array('sql' => $rows);
			return $data;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function __destruct () {
		$this -> con -> cerrar();
	}
}

