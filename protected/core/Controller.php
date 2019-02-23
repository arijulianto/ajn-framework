<?php
namespace Ajn;

use Ajn\app\model;
use Ajn\app;

class Controller{
	public $environment;
	public $capsule;
	public $controller_path;
	public $req_controller;
	public $req_model;
	public $view_render;
	public $req_param;
	public $req_method;
	public $load ;

	function __construct(){
		require __DIR__.'/App.php';
		$this->load = new App;
	}

	function render($viewName, $data=[]){
		$r = new \ReflectionClass($this);
		$class = substr_replace($r->name, '', -10, 10);
		$class = \Ajn::fromRoute($class);
		$view_path = \Ajn::getPath().'/view/'.$class.'/'.$viewName.'.php';
		if(is_file($view_path)){
			extract($data);
			$output = require $view_path;
		}else{
			header('HTTP/1.1 404 Not Found');
        	die('Unable to load view '.$viewName);
		}
	}
}
