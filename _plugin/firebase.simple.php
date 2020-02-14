<?php
class Firebase{
    private $api_key;
    private $data = array('vibrate'=>1, 'sound'=>1);
    private $headers;
    private $target;
    private $expired;// = 604800;

    function __construct($api){
        $this->api_key = $api;
        $this->headers = array(
            'Authorization: key=' . $api,
            'Content-Type: application/json'
        );
    }

    function setMessage($title, $text=null){
        $this->data['title'] = $title;
        if($text) $this->data['text'] = $text;
    }

    function setExpired($hari){
        $this->expired = $hari*86400;
    }

    function setTarget($target){
        $this->target = $target;
    }

    function addTarget($target){
        if($this->target && !is_array($this->target)){
            $this->target[] = $this->target;
        }
        $this->target[] = $target;
    }

    function setIcon($drawable){
        $this->data['icon'] = $drawable;
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
        curl_setopt($ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
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
