<?php

// namespace Ajn\app\controller;
use Ajn\Controller;

class HomeController extends Controller{
	public function index(){
		return $this->render('index', ['test'=>123]);
	}

	public function page(){
		return $this->render('page');
	}

}

