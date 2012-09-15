<?php

abstract class Controller_Generic {
	private $conditionsTable = array();

	public function addCondition($condition, $callMethod)
	{
		$callMethod = (string)$callMethod; // for cast to string
		if(key_exists($callMethod, $this->conditionsTable)) {
			throw new Exception('Duplicate condition exception.');
		}

		if (!method_exists($this, $callMethod)){
			throw new Exception('CallMethod not defined.');
		}
		$this->conditionsTable[$callMethod] = $condition;
	}

	public function run()
	{
		if(count($this->conditionsTable) < 1){
			throw new Exception('No conditions to evaluate exception.');
		}

		foreach($this->conditionsTable as $method => $condition){
			if($condition) return call_user_func(array($this, $method));
		}

		throw new Exception("No condition evaluated to true Exception");
	}

	public function renderPageStart()
	{
		include 'Templates/pageStart.html';
	}

	public function renderPageEnd()
	{
		include 'Templates/pageEnd.html';
	}
}