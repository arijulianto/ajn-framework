<?php
class Image{
	private $source;
	private $mime;
	private $ext;
	private $path = '';

	public $name;
	public $filename;
	public $size = 0;
	public $width = 0;
	public $height = 0;

	function __construct($input, $path=''){
		$valid_mime = array('image/jpeg','image/png','image/gif');
		$finfo = getimagesize($input['tmp_name']); 
		if(in_array($finfo['mime'], $valid_mime)){
			$this->source = $input['tmp_name'];
			$this->filename = $input['name'];
			$this->size = $input['size'];
			$this->path = $path;
			$this->width = $this->width ? $this->width : $finfo[0];
			$this->height = $this->height ? $this->height : $finfo[1];
			$this->ext = strtolower(substr(strrchr($input['name'], '.'), 1));
			$this->name = substr_replace($input['name'], '', strripos($input['name'],'.'.$this->ext), strlen($this->ext)+1);

		}else{
			if(!$input['tmp_name'])
				die('Invalid source input!');
			else
				die('Invalid image file! Only accept JPG, PNG or GIF');
		}
	}

	function upload($name=null){
		$this->name = $name!=null ? $name : $this->name;
		$this->filename = $this->name.'.'.$this->ext;
		if($this->size>0){
			move_uploaded_file($this->source, $this->path.$this->filename);
		}
	}

	function resize($w, $h, $name=null){
		if($w==$this->width && $h==$this->height){return false;}
		$path = $this->path;
		$name = $name!=null ? $name : $this->name;
		$filename = $name!=null ? $name.'.'.$this->ext : $this->filename;
		$ratio_ori = $this->width / $this->height;
		$ratio_new = $w / $h;


		if($ratio_new > $ratio_ori){
		   $w = $w * $ratio_ori;
		}else{
		   $h = $w / $ratio_ori;
		}

		$img = '';
		$ext = $this->ext;
		
		if($ext == 'gif'){ 
			$img = imagecreatefromgif($path.$this->filename);
		}else if($ext =='png'){ 
			$img = imagecreatefrompng($path.$this->filename);
		}else{ 
			$img = imagecreatefromjpeg($path.$this->filename);
		}

		$tci = imagecreatetruecolor($w, $h);
		imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $this->width, $this->height);

		if($ext == 'gif'){ 
			imagegif($tci, $path.$filename);
		}else if($ext =='png'){ 
			imagepng($tci, $path.$filename);
		}else{ 
			imagejpeg($tci, $path.$filename, 84);
		}
		if($name==null){
			$this->width = $w;
			$this->height = $h;
		}
		if($img) imagedestroy($img);
		if($tci) imagedestroy($tci);
	}

	function crop($w,$h,$x1=0,$y1=0,$name=null) {
		if($w==$this->width && $h==$this->height){return false;}
		$path = $this->path;
		$name = $name!=null ? $name : $this->name;
		$filename = $name!=null ? $name.'.'.$this->ext : $this->filename;

		$w = $w > $this->width ? $this->width : $w;
		$h = $h > $this->height ? $this->height : $h;
	    
	    $ext = $this->ext;
		$img = '';
		if($ext == 'gif'){ 
			$img = imagecreatefromgif($path.$this->filename);
		}else if($ext =='png'){ 
			$img = imagecreatefrompng($path.$this->filename);
		}else{ 
			$img = imagecreatefromjpeg($path.$this->filename);
		}
	     
	    $source_aspect_ratio = $this->width / $this->height;
		$desired_aspect_ratio = $w / $h;

		if ($source_aspect_ratio > $desired_aspect_ratio) {
		    $temp_height = $h;
		    $temp_width = ( int ) ($h * $source_aspect_ratio);
		} else {
		    $temp_width = $w;
		    $temp_height = ( int ) ($w / $source_aspect_ratio);
		}

		$img2 = imagecreatetruecolor($temp_width, $temp_height);
		imagecopyresampled($img2, $img, $x1, $y1, 0, 0, $temp_width, $temp_height, $this->width, $this->height);

		//$x0 = ($temp_width - $w) / 2;
		//$y0 = ($temp_height - $h) / 2;

		$tci = imagecreatetruecolor($w, $h);
		imagecopy($tci, $img2, 0, 0, $x1, $y1, $w, $h);
	     
	    if($ext == 'gif'){ 
			imagegif($tci, $path.$filename);
		}else if($ext =='png'){ 
			imagepng($tci, $path.$filename);
		}else{ 
			imagejpeg($tci, $path.$filename, 84);
		}
		if($img) imagedestroy($img);
		if($img2) imagedestroy($img2);
		if($tci) imagedestroy($tci);
	}
}
