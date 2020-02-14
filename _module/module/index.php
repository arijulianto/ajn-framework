<?php
class Module extends Framework{
    function config(){
        $this->autoLoad = true;
        $this->loadMode = 'full';
    }
}