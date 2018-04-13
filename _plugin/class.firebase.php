<?php
class Firebase{
	private $api_key;
	private $data = array('vibrate'=>1, 'sound'=>1);
	private $headers;
	private $topic;
	private $target;
	private $targets;
	private $expired;// = 604800;

	function __construct($api){
		$this->api_key = $api;
		$this->headers = array	(
			'Authorization: key=' . $api,
			'Content-Type: application/json'
		);
	}

	function data($data){
		foreach($data as $k=>$v) $this->data[$k] = $v;
	}

	function addData($key,$val){
		$this->data[$key] = $val;
	}

	function setTopic($topic){
		$this->topic = $topic;
	}

	function setExpired($sec){
		$this->expired = $sec;
	}

	function setTarget($target){
		$this->target = $target;
	}

	function addTarget($target){
		$this->targets[] = $target;
	}

	function send(){
		$fields = array(
			'data' => $this->data
		);
		if(is_array($this->target)){
			$fields['registration_ids'] = is_array($this->target) ? $this->target : [$this->target];
		}else{
			$fields['to'] = $this->target;
		}
		
		if($this->expired) $fields['time_to_live'] = $this->expired;
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
