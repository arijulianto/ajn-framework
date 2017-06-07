<?php
/*
======================
Plugin UPLOAD GAMBAR
======================
Oleh: Ari Julianto
Dibuat: Desember 2014
Update: Mei 2017


== Cara Penggunaan ==
if($_FILES['file']['size']>0){
    $upload = new upload($_FILES['NAMA_INPUT_GAMBAR']); // parameter: new upload(source, location) -- source: required location: ''
    $upload->add($_FILES['NAMA_INPUT_GAMBAR']); // parameter: add(source, location) -- source: required location: '' ~ alternative
    $upload->upload(); // parameter: upload(newName,maxSize) -- newName: default maxSize: default
    $upload->resize(800); // parameter: resize(maxWidth, newFilename) -- default param: null
    $upload->thumbnail(); // parameter: thumbnail(maxWidth, prefix); -- default: maxWidth=50 prefix:t_
}
*/

class upload{
    public $source = null;
    public $maxWidth = 400;
    public $maxHeight = 400;
    public $maxDimension = 400;
    public $thumbDimension = 50;
    public $path = null;
    public $fullpath = null;
    public $name = null;

    function __construct($source='',$dir=''){
        if($source){
            $this->source = $source;
            $this->name = $source['name'];
        }
        if($dir){
            $this->path = $dir;
            $this->fullpath = ($dir ? $dir.'/' : '') . $this->name;
        }
    }

    function add($source,$dir=''){
        $this->path = $dir;
        $this->source = $source;
        $this->name = $source['name'];
        $this->fullpath = ($dir ? $dir.'/' : '') . $this->name;
    }
    
    function upload($name=null,$resize=0){
        if($name){
            $this->name = $name;
            $this->fullpath = ($this->path ? $this->path.'/' : '') . $name;
        }
        if($this->source && $this->name){
            $upload = move_uploaded_file($this->source['tmp_name'], $this->fullpath);
            if($upload){
                return $this->name;
                if($resize>0){
                   $this->resize($resize);
                }
            }else{
                die('<div class="warning">Upload failed, please try again!</div>');
            }
        }else{
            die('<div class="warning">Upload failed. Please set source and target name file!</div>');
        }
    }
    
    function resize($max, $imgFile=null) {
        $originalFile = $this->fullpath;
        $imgFile = ($this->path ? $this->path.'/' : '') . ($imgFile ? $imgFile : $this->name);
        
        $info = getimagesize($originalFile);
        $mime = $info['mime'];
    
        switch ($mime) {
                case 'image/jpg':
                case 'image/jpeg':
                        $image_create_func = 'imagecreatefromjpeg';
                        $image_save_func = 'imagejpeg';
                        break;
    
                case 'image/png':
                        $image_create_func = 'imagecreatefrompng';
                        $image_save_func = 'imagepng';
                        break;
    
                case 'image/gif':
                        $image_create_func = 'imagecreatefromgif';
                        $image_save_func = 'imagegif';
                        break;
    
                default:
                        die('Unknown image type.');
        }
    
        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);
        
        $newDimension = $this->calculateDimensions($width,$height,$max);
        $newWidth = $newDimension['width'];
        $newHeight = $newDimension['height'];
        
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        $image_save_func($tmp, $imgFile);
        imagedestroy($tmp);
    }
    
    function thumbnail($dimension=null,$prefix=null) {
        $targetFile = ($prefix ? $prefix : 't_') . $this->name;
        $dimension = $dimension ? $dimension : $this->thumbDimension;
        $this->resize($dimension,$targetFile);
    }
    
    
    function base64_to_image($base64_string, $output_filename, $path=null) {
        $exts = array('jpg'=>'jpg','jpeg'=>'jpg','png'=>'png','gif'=>'gif');
        $data = explode(';base64,', $base64_string);
        $ext = end(explode('/',$data[0]));
        $ext = $exts[$ext];
        $output_filename = strtolower($output_filename);
        $output_filename = substr($output_filename,-(strlen($ext)+1),strlen($ext)+1)==".$ext" ? $output_filename : "$output_filename.$ext";
        $path = $path ? "$path/" : '';
        $ifp = fopen($path.$output_filename, 'wb'); 
        fwrite($ifp, base64_decode($data[1])); 
        fclose($ifp); 
        return $path.$output_filename;
    }
    
    function calculateDimensions($width,$height,$max){
        $ratio = $width/$height;
        if($ratio > 1){
            $width = $max;
            $height = $max/$ratio;
        }else{
            $width = $max*$ratio;
            $height = $max;
        }
        $res = array('height'=>$height,'width'=>$width);
        return $res;
    }
}
