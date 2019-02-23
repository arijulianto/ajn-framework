<?php

// use Ajn\app;
use Ajn\app\controller;
use Opis\Database\Database;
use Opis\Database\Connection;

class Ajn {

	/*
	 * Class Properties Declaration
	 */

	public $environment;
	public $capsule;
	public $controller_path;
	public $req_controller;
	public $req_model;
	public $view_render;
	public $req_param;
	public $req_method;
	public $load;

	private $slug;
	private $controller;
	// private $controller_path;
	private $module;
	private $action;
	private $param;
	private $method;
	private $model;

	private $app_path;
	private $config;


	function __construct(){
		$this->config = require __DIR__ . '/../config/config.php';
	}


	public function init(){
		if (defined('ENVIRONMENT')){
			switch (ENVIRONMENT){
				case 'development':
				case 'dev':
					error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
				break;

				case 'testing':
				case 'production':
					error_reporting(0);
				break;

				default:
					exit('The application environment is not set correctly.');
			}
		}else{
			exit('The application environment is not set correctly.');
		}
		$path = trim($_SERVER['PATH_INFO'], '/');
		$this->slug = explode('/', $path);

		// set module / controller / action
		if($path==''){
			$this->controller = (isset($this->config['controller']['default']) ? $this->toRoute($this->config['controller']['default']).'Controller' : 'IndexController');
			$this->action = 'index';
		}else{
			if(isset($this->slug[2])){
				$this->module = $this->slug[0];
				$this->controller = $this->toRoute($this->slug[1]).'Controller';
				$this->action = $this->slug[2];
			}else{
				$this->controller = $this->toRoute($this->slug[0]).'Controller';
				$this->action = $this->slug[1] ? $this->slug[1] : 'index';
			}
		}
		$this->execute();
	}

	public function dir($dir){
		$dir = str_replace('\\','/', $dir);
		$dir = trim($dir, '/');
		$test = explode('/', $dir);
		if(end($test)=='protected')
			$this->app_path = $dir;
		else
			$this->app_path = $dir.'/protected';
	}

	

	private function execute(){
		if($this->module){
			if(is_dir($this->app_path.'/module/'.$this->module)){
				if(is_file($this->app_path.'/module/'.$this->module.'/'.$this->controller.'.php')){
					$this->module = $this->slug[0];
					$this->controller = $this->toRoute($this->slug[1]).'Controller';
					$this->action = $this->slug[2];
					$this->controller_path = 'module/'.$this->module.'/'.$this->controller.'.php';
					$this->param = array_slice($this->slug, 3);
				}else{
					header('HTTP/1.1 404 Not Found');
            		die('Unable to load '.$this->controller.' on module '.$this->module);
				}
			}else{
				unset($this->module);
				$this->controller = $this->toRoute($this->slug[0]).'Controller';
				$this->action = $this->slug[1];
				$this->controller_path = 'controller/'.$this->controller.'.php';
				$param = array_slice($this->slug, 2);
				if($param){
					$this->param = $param;
					/*foreach($param as $i=>$val){
						$param['data']['slug'.($i+1)] = $val;
					}
					$this->param = $param['data'];*/
				}
				if(!is_file($this->app_path.'/controller/'.$this->controller.'.php')){
					header('HTTP/1.1 404 Not Found');
            		die('Unable to load '.$this->controller);
				}
			}
		}else{
			if(is_file($this->app_path.'/controller/'.$this->controller.'.php')){
				$this->controller_path = 'controller/'.$this->controller.'.php';
				$param = array_slice($this->slug, 2);
				if($param){
					$this->param = $param;
					/*foreach($param as $i=>$val){
						$param['data']['slug'.($i+1)] = $val;
					}
					$this->param = $param['data'];*/
				}
			}else{
				header('HTTP/1.1 404 Not Found');
        		die('Unable to load '.$this->controller);
			}
		}
	}

	public function run(){
		include $this->app_path.'/'.$this->controller_path;
		$controller = $this->controller;
		$action = $this->action;
		$param = $this->param;
		$app = new $controller();
		if(method_exists($app, $action)){
			$app->$action($param);
		}else{
			header('HTTP/1.1 404 Not Found');
        	die('Unable to load action '.$action.' on controller '.$this->controller);
		}
	}

	public static function fromRoute($name){
		$output = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
		return $output;
	}

	public static function toRoute($name){
		$output = str_replace('-', '', ucwords($name,'-'));
		$output = str_replace('_', '', ucwords($output,'_'));
		return $output;
	}

	public static function getPath(){
		$dir = explode('/', str_replace('\\','/',__DIR__));
		array_pop($dir);
		return implode('/', $dir);
	}

	public static function getSetting($name=''){
		if($name!='')
			return $this->config[$name];
		else
			return $this->config;
	}
}
