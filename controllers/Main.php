<?php

Class Main extends App {

	static $urlPattern = array(
		'/lookups\/?(.*)/' => array('Lookup'),
		'/users\/?(.*)/' => array('Users'),
		'/groups\/?(.*)/' => array('Groups'),
		'/table\/?(.*)/' => array('Table'),
		'/crud\/?(.*)/' => array('Crud'),
		'/event-update\/?(.*)/' => 'eventUpdate',
		'/machine-shop\/?(.*)/' => 'machineShop'
	);

	function __construct() {
		$this->lib(array('Uri', 'databases/Query'));
	}

	function index() {
		echo 'index';
		
	}
	
	function model() {
		echo "\n";
		//echo $this->libs->Uri->get(1);
		
		$this->helper('inflector');
		
		if($this->libs->Uri->get(1) == 'build') {
			$tableName = $this->libs->Uri->get(2);
			
			$modelName =  camelize($tableName);
			$modelName[0] = strtoupper($modelName);
			
			$fieldInfo = $this->libs->Query->results('object', 'DESCRIBE `' . $tableName . '`');
			D::log($fieldInfo, 'f info');
			foreach($fieldInfo as $field) {
				if($field->Key == 'PRI') {
					$priKey = $field->Field;
					break;
				}
			}
			
			
			echo '<?' . "\n";
			echo 'class ' . $modelName . ' extends SweetModel {' . "\n";
			echo "\t" . "\n";
			echo "\t" . 'var $tableName = \'' . $tableName . '\';' . "\n";
			echo "\t" . 'var $pk = \'' . $priKey . '\';' . "\n";
			echo "\t" . "\n";
			echo "\t" . 'var $fields = array(' . "\n";
			foreach($fieldInfo as $field) {
				$tpye = explode(' ', $field->Type);
				$type = explode('(', $tpye[0]);
				if(isset($type[1])) {
					$type[1] = substr($type[1], 0, -1);
				}
				echo "\t\t" . '\'' . $field->Field . '\' => array(' . join(', ', array_map(function($t) {
					return '\'' . $t . '\'';
				}, $type))  . '),'  . "\n";
			}
			echo "\t" . ');' . "\n";
			echo "\t" . "\n";
			echo "\t" . 'var $relationships = array();' . "\n";
			echo "\t" . "\n";
			echo "\t" . 'function __construct() {' . "\n";
			echo "\t\t" . "\n";
			echo "\t" . '}' . "\n";
			echo "\t" . "\n";
			echo "\t" . "\n";
			echo '}';
			
/*

	var $relationships = array(
		'rawId' => array('BianchiRawLookup', 'id'),
		
		'orgGuns' => array(
			'id' => array('GunMakeTranslate', 'bMake')
		)
	);
	
	function __construct() {
	
	}
	
	

}





			    [65] => stdClass Object
        (
            [Field] => trade_sales
            [Type] => double(16,2)
            [Null] => NO
            [Key] => MUL
            [Default] => 0.00
            [Extra] => 
        )
        
        var $tableName = 'bianchiGuns';
		
		var $pk = 'id';
		
		'id' => array('int', 11),
		'manufacturer' => array('varchar', 45),
		'model' => array('varchar', 45),
		'bbl' => array('varchar', 15)
*/

			
			
			
		}
		
		echo "\n";
	}
	
	function test() {
		echo 'what';
	}	
	
	function __DudeWheresMyCar() {
		header('HTTP/1.0 404 Not Found');
		
		return $this->libs->Template->set(array(
			'title' => '404 Error',
			'content' => T::get('parts/common/404'),
		))->render('bases/default');
	}
}