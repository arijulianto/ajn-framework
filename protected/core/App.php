<?php
namespace Ajn;

// use Ajn\model\app;

class App{
	function model($name){
		$class = \Ajn::toRoute($name);
		// include require __DIR__.'/../model/'.$class.'.php';
		// $model = new $class;
		return $model;
	}

	function database($name){
		echo 'Load model '.$name;
	}
}