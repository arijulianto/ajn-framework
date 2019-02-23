<?php
use Ajn\Model;

namespace Ajn\app\model;

class User extends Model{
	function tableName(){
		return 'bot_chat';
	}

	function getUser(){
		$data = $this->db->query("SELECT * from bot_chat")->results();
		return $data;
	}
}