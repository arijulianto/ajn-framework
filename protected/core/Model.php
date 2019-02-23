<?php
namespace Ajn\app;

use Ajn\db;

class Model{
	private $db;

	public function __construct(){
		/*$connection = new Connection("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
		$this->db = new Database($connection);*/
		$this->db = new MySQL(\Ajn::getSetting());
	}
}
