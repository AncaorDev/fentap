<?php namespace model;
/**
* 
*/
class Model 
{
	private $status;
	private $count;
	function __construct()
	{
	}

	public function status($status){
		if ($status) {
				return $statusTable = $this->statusTable();
			} else {
				return $statusTable = "";
			}
	}
}