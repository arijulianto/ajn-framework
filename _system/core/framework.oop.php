<?php
class Framework{

    function __construct(){
        if(method_exists($this, 'config')){
            $this->config();
        }
    }

    function error($err){
        echo $err;
    }
    // simpan di class di atasnya
    function Run(){
        global $tmp_slug;

        if($this->autoLoad==true){
            if(isset($slug1)){
                if(method_exists($this, $slug1)){
                    $this->$slug1();
                }else{
                    $this->error('error undefined '.$slug1.'()');
                }
            }else{
                if(method_exists($this, 'index')){
                    $this->index();
                }else{
                    $this->error('error undefined index()');
                }
            }
        }
    }

    function load($action){
        echo MODULE_DIR.'/'.$action.'.php';
    }

}