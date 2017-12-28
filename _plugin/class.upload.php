<?php
/*
======================
Plugin UPLOAD GAMBAR
======================
Oleh: Ari Julianto
Dibuat: Desember 2014
Update: Oktober 2017


== Cara Penggunaan ==
if($_FILES['file']['size']>0){
    $upload = new upload($_FILES['NAMA_INPUT_GAMBAR'], MEDIA_PATH.'images'); // parameter: new upload(source, location) -- source: required location: ''
    //$upload->add($_FILES['NAMA_INPUT_GAMBAR']); // parameter: add(source, location) -- source: required location: '' ~ alternative
    $hasil_upload = $upload->upload(null, 1024); // parameter: upload(newName,maxSize) -- newName: default maxSize: default
    //$upload->resize(800); // parameter: resize(maxWidth, newFilename) -- default param: null
    $upload->thumbnail(300, 'a_'); // parameter: thumbnail(maxWidth, prefix); -- default: maxWidth=50 prefix:t_
}
*/

class upload{
    public $source = null;
    public $maxDimension = 500;
    public $thumbDimension = 50;
    public $path = '';
    public $fullpath = '';
    public $name = '';

    function __construct($source='',$dir=''){
        if($source){
            $this->source = $source;
            $this->name = $source['name'];
        }
        $this->path = $dir;
        $this->fullpath = ($dir ? $dir.'/' : '') . $this->name;
    }

    function add($source,$dir=''){
        $this->path = $dir;
        $this->source = $source;
        $this->name = $source['name'];
        $this->fullpath = ($dir ? $dir.'/' : '') . $this->name;
    }

    function upload($name=null,$resize=0){
        if($name){
            $exts = array('jpg'=>'jpg','jpeg'=>'jpg','png'=>'png','gif'=>'gif');
            $ext = end(explode('.',strtolower($name)));
            if($exts[$ext]){
                $this->name = $name;
            }else{
                $ext = end(explode('.',strtolower($this->name)));
                $this->name = $name.'.'.$ext;
            }
            $this->fullpath = ($this->path ? $this->path.'/' : '') . $this->name;
        }
        if($this->source && $this->name){
            $finfo = getimagesize($this->source['tmp_name']);
            $upload = move_uploaded_file($this->source['tmp_name'], $this->fullpath);
            if($upload){
                if($resize>0 && ($resize<$finfo[0] || $resize<$finfo[1])){
                    $this->resize($resize);
                }
                return $this->name;
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
        if($mime=='image/png'){
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
        }
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $image_save_func($tmp, $imgFile);
        imagedestroy($tmp);
    }



    function crop($name=null, $width = 200, $height = 200){
        $quality = 70;
        if($name){
            $exts = array('jpg'=>'jpg','jpeg'=>'jpg','png'=>'png','gif'=>'gif');
            $ext = end(explode('.',strtolower($name)));
            if($exts[$ext]){
                $this->name = $name;
            }else{
                $ext = end(explode('.',strtolower($this->name)));
                $this->name = $name.'.'.$ext;
            }
            $this->fullpath = ($this->path ? $this->path.'/' : '') . $this->name;
        }
        $image_data = getimagesize( $this->source['tmp_name'] );
        switch($image_data['mime']){
            case 'image/gif':
            $get_func = 'imagecreatefromgif';
            $suffix = ".gif";
            break;
            case 'image/jpeg';
            $get_func = 'imagecreatefromjpeg';
            $suffix = ".jpg";
            break;
            case 'image/png':
            $get_func = 'imagecreatefrompng';
            $suffix = ".png";
            break;
        }
        $img_original = call_user_func( $get_func, $this->fullpath );
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round( $old_width / $old_height, 2 );
        $desired_ratio_after = round( $width / $height, 2 );
        $desired_ratio_before = round( $height / $width, 2 );
        $new_image = imagecreatetruecolor( $width, $height );
        if( $current_ratio > $desired_ratio_after ){
            $new_width = $old_width * $height / $old_height;
        }
        if( $current_ratio > $desired_ratio_before && $current_ratio < $desired_ratio_after ){
            if( $old_width > $old_height ){
                $new_height = max( $width, $height );
                $new_width = $old_width * $new_height / $old_height;
            }
            else {
                $new_height = $old_height * $width / $old_width;
            }
        }
        if( $current_ratio < $desired_ratio_before  ){
            $new_height = $old_height * $width / $old_width;
        }
        $width_ratio = $old_width / $new_width;
        $height_ratio = $old_height / $new_height;
        $src_x = floor( ( ( $new_width - $width ) / 2 ) * $width_ratio );
        $src_y = round( ( ( $new_height - $height ) / 2 ) * $height_ratio );
        imagecopyresampled( $new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height );
        imagejpeg( $new_image, $this->fullpath, $quality  );
        imagedestroy( $new_image );
        imagedestroy( $img_original );
        return true;
    }

    function cropImage($source, $width=100, $height=100, $fileName=false){
        $thumb_img  =   imagecreatefromjpeg($source);
        $fileName = $fileName ? $fileName : $source;
        list($w, $h) = getimagesize($source);
        if($w > $h) {
            $new_height =   $height;
            $new_width  =   floor($w * ($new_height / $h));
            $crop_x     =   ceil(($w - $h) / 2);
            $crop_y     =   0;
        }else{
            $new_width  =   $width;
            $new_height =   floor( $h * ( $new_width / $w ));
            $crop_x     =   0;
            $crop_y     =   ceil(($h - $w) / 2);
        }

        $tmp_img = imagecreatetruecolor($width,$height);
        imagecopyresampled($tmp_img, $thumb_img, 0, 0, $crop_x, $crop_y, $new_width, $new_height, $w, $h);
        imagejpeg($tmp_img,$fileName,80);
        imagedestroy($tmp_img);
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

    function image_to_base64($file=null){
        $image = $file ? $file : $this->fullpath;
        $type = pathinfo($image, PATHINFO_EXTENSION);
        $data = file_get_contents($image);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
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
