<?php
// modul class informasi pengunjung
class client{
  public $ip;
  function __construct () {
    global $ip, $agent, $client, $client_url, $loc, $loc_url, $isp, $isp_url, $provider, $countryName, $browser, $browser_ver;
    $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $this->agent = $_SERVER['HTTP_USER_AGENT'];
  }

  public function get($type){
    return $this->$type();
  } 
  
  public function browser($type=NULL){ // deteksi browser (default: nama browser)
    global $agent;
    $browser        =   "Unknown";
    $browser_array  =   array(
                            // Computer Browser
                            '/trident/i' => 'Internet Explorer',
                            '/msie/i' => 'Internet Explorer',
                            '/firefox/i' => 'Mozilla Firefox',
                            '/waterfox/i' => 'Waterfox',
                            '/safari/i' => 'Safari',
                            '/Firebird/' => 'Firebird',
                            '/K-Meleon/' => 'K-Meleon',
                            '/chrome/i' => 'Google Chrome',
                            '/(comodo_dragon)|(Dragon)/i' => 'Comodo Dragon',
                            '/IceDragon/i' => 'Comodo IceDragon',
                            '/flock/i' => 'Flock',
                            '/konqueror/i' => 'Konqueror',
                            '/seamonkey/i' => 'SeaMonkey',
                            '/rockmelt/i' => 'RockMelt',
                            '/omniweb/i' => 'OmniWeb',
                            '/(opera)|(opr)/i' => 'Opera',
                            '/maxthon/i' => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/(netscape)|(navigator)/i' => 'Netscape Navigator',
                            '/avant browser/i' => 'Avant Browser',
                            
                            // Mobile Browser
                            '/android/i' => 'Android Webkit Browser',
                            '/blackberry/i' => 'BlackBerry',
                            '/bolt/i' => 'Bolt',
                            '/symbianos/i' => 'Browser for S60',
                            '/gobrowser/i' => 'Go Browser',
                            '/iemobile/i' => 'IE Mobile',
                            '/opera mini/i' => 'Opera Mini',
                            '/opera mobi/i' => 'Opera Mobile',
                            '/(ucweb)|(ucbrowser)/i' => 'UC Browser',
                            '/semc-browser/i' => 'SEMC-Browser',
                            '/fennec/i' => 'Firefox Fennec'
                            );

    foreach ($browser_array as $regex => $value) { 
        if (preg_match($regex, $this->agent)) 
            $browser    =   $value;
    }


    if($type=="name")
      $output =  $browser;
    elseif($type=="ver" || $type=="version")
      $output = $this->browserver($browser);
	elseif($type=="namever")
      $output = $browser." ".$this->browserver($browser);
    else
      $output = $browser;
    return $output;
  }
  
  private function browserver($bname=NULL){
  $bname = $bname ? $bname : $this->browser();
  $version = 0;
  if($bname=="Internet Explorer"){
		if(strpos($this->agent, 'Trident')){
			$version	= explode('rv:', $this->agent);
			$version	= explode(')', $version[1]);
			$version	= $version[0];
		}else{
			$version	= explode("; ",$this->agent);
			$version	= explode(" ",$version[1]);
			$version	= $version[1];
		}
	}
	elseif($bname=="Opera"){
		$version	= explode("OPR/",$this->agent) ? explode("OPR/",$this->agent) : explode("Version/",$this->agent);
		$version	= $version[1];
	}
	elseif($bname=="Netscape Navigator"){
		$version	= explode("Navigator/",$this->agent);
		$version	= $version[1];
	}
	elseif($bname=="Flock"){
		$version	= explode("Flock/",$this->agent);
		$version	= $version[1];
	}
	elseif($bname=="Mozilla Firefox"){
		$version	= explode("Firefox/",$this->agent);
		$version	= $version[1];
	}
	elseif($bname=="Waterfox"){
		$version	= explode("Waterfox/",$this->agent);
		$version	= $version[1];
	}
	elseif($bname=="Google Chrome"){
		$version	= explode("Chrome/",$this->agent);
		$version	= explode(" ",$version[1]);
		$version	= $version[0];
	}
	elseif($bname=="Maxthon"){
		$version	= explode("Maxthon/",$this->agent);
		$version	= explode(" ",$version[1]);
		$version	= $version[0];
	}
	elseif($bname=="Safari"){
		$version	= explode("Version/",$this->agent);
		$version	= explode(" ",$version[1]);
		$version	= $version[0];
	}
	$ver = $version>0 ? $version : "";
	return $ver;
  }
  
  private function os(){  // deteksi nama system operasi
    $OS = "Unknown";
    $OSList = array(
    'Android' => 'Android',
    'BlackBerry OS' => '(BlackBerry)|(BB10)',
    'BlackBerry Tablet OS 1' => 'RIM Tablet OS 1.',
    'BlackBerry Tablet OS 2' => 'RIM Tablet OS 2.',
    'Windows Phone 6.5' => 'Windows Phone 6.5',
    'Windows Phone 7' => 'Windows Phone 7.0',
    'Windows Phone 7' => 'Windows Phone OS 7.0',
    'Windows Phone 7.5' => 'Windows Phone OS 7.5',
    'Windows Phone 8' => '(Windows Phone 8.0)|(Windows Phone OS 8.0)',
    'Windows Phone 8.1' => '(Windows Phone OS 8.1)|(Windows Phone 8.1)',
    'Windows Mobile' => 'Windows Mobile',
    'Windows CE' => 'Windows CE',
    'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
    'Windows Server 2003' => '(Windows NT 5.2)',
    'Windows Vista' => '(Windows NT 6.0)',
    'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
    'Windows 8' => '(Windows NT 6.2)',
	'Windows 8.1' => '(Windows NT 6.3)',
    'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)',
    'Windows NT' => 'Windows NT',
    'Windows ME' => '(Windows ME)|(Win 9x)',
    'Symbian' => '(SymbianOS)|(SymbOS)|(Series 60)',
    'Java ME' => '(J2ME)|(MIDP)',
    'Open BSD' => 'OpenBSD',
    'Free BSD' => 'FreeBSD',
    'Sun OS' => 'SunOS',
    'Linux' => '(Linux)|(X11)',
    'iOS' => '(iPod)|(iPad)|(iPhone)|(CPU OS)|(CPU iPhone OS)',
    'OS X' => 'Mac OS X',
    'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
    'QNX' => 'QNX',
    'BeOS' => 'BeOS',
    'OS/2' => 'OS/2',
    'PalmOS' => 'PalmOS',
    'Mobile' => '(web|hpw)os',
    'Chinese Symbian 3G' => 'GoBrowser',
    'JVM (Java)' => 'Java',
    'Search Bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)',
    'Unknown' => ';');
    
    foreach($OSList as $OS=>$Match){
       if (eregi($Match, $this->agent))break;
    }
    return $OS;
  }
  
  private function ip(){ // deteksi IP address
    return $this->ip;
  }

  private function device(){ // deteksi perangkat seluler (Merk & Model ponsel)  
    $array_brand = array(
						'/nokia/i' => 'Nokia',
						'/motorola|MOT(-)?/i' => 'Motorola',
						'/sony/i' => 'Sony',
						'/ericsson/i' => 'Ericsson',
						'/sonyericsson/i' => 'SonyEricsson',
						'/htc(-|_)?/i' => 'HTC',
						'/samsung|(SEC-|SGH-|SCH-|SHW-|SG-|GT-|SM-|SC-)([a-zA-Z0-9-.]+)/i' => 'Samsung',
						'/asus/i' => 'Asus',
						'/blackberry|rim([a-zA-Z0-9-.])/i' => 'Blackberry',
						'/bb10/i' => 'Blackberry 10',
						'/philips/i' => 'Philis',
						'/lg(-)?/i' => 'LG',
						'/lge(-)?/i' => 'LGE',
						'/hp/i' => 'HP',
						'/Lenovo/i' => 'Lenovo',
						'/Asus/i' => 'Asus',
						'/Panasonic/i' => 'Panasonic',
						'/Toshiba/i' => 'Toshiba',
						'/acer(_|\s)/i' => 'Acer',
						'/nexian/i' => 'Nexian',
						'/nexus/i' => 'Google Nexus',
						'/dell/i' => 'DELL',
						'/iPhone/i' => 'Apple iPhone',
						'/iPad|iProd/i' => 'Apple iPad',
						'/iPod/i' => 'Apple iPod',
						'/PlayBook/i' => 'Blackberry Playbook',
						'/(Huawei|ideos\s)/i' => 'Huawei',
						'/Haier|HTIL-/i' => 'Haier',
						'/IdeaTab/i' => 'Levono',
						'/SHARP|SBM([a-zA-Z0-9]+)SH|-SH|SH-/i' => 'Sharp',
						'/sie-/i' => 'Siemens',
						'/ZTE-/i' => 'ZTE',
						'/Sanyo/i' => 'Sanyo',
						'/Venera/i' => 'Venera'
					);
	foreach($array_brand as $regex=>$value) { 
        if (preg_match($regex, $this->agent)) 
            $output = $value;
    }
    return $output;
  }
  
 
  public function is_mobile(){ // cek perangkat yang digunakan adalah seluler
  if(preg_match('/android|mobile|avantgo|bada\/|blackberry|rim|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|hp-tablet|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|bolt|windows (ce|phone)|xda|xiino/i',$this->agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($this->agent,0,4)))
    return TRUE;
  else
    return FALSE;
  }
}
?>