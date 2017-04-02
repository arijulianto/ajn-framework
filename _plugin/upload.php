<?php
/*
======================
Plugin UPLOAD GAMBAR
======================
Oleh: Ari Julianto
Dibuat: Desember 2014


== Cara Penggunaan ==
if($_FILES['file']['size']>0){
    $upload->path = FOLDER_LOKASI_UPLOAD_GAMBAR;
    $upload->set($_FILES['NAMA_INPUT_GAMBAR']);
    $upload->upload();
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
    
    function __construct($dir = __FILE__){
        $this->path = str_replace('\\','/',dirname($dir));
    }
    
    function set($source){
        if(!$this->name){
            die('<div class="warning">The target name must be st by $upload->name = NAME_OF_FILE!</div>');
        }else{
            $this->source = $source;
            $this->fullpath = $this->path . '/' . $this->name;
        }
    }
    
    
    function upload(){
        if($this->source && $this->name){
            $upload = move_uploaded_file($this->source, $this->fullpath);
            if($upload){
                return $this->fullpath;
            }else{
                die('<div class="warning">Upload failed, please try again!</div>');
            }
        }else{
            die('<div class="warning">Upload failed. Please set source and target name file!</div>');
        }
    }
    
    function resize($max, $targetFile=null) {
        $originalFile = $this->fullpath;
        $targetFile = $this->path . '/' . ($targetFile ? $targetFile : $this->name);
        
        $info = getimagesize($originalFile);
        $mime = $info['mime'];
    
        switch ($mime) {
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
        
    
        if (file_exists($targetFile)) {
                unlink($targetFile);
        }
        
        $image_save_func($tmp, $targetFile);
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