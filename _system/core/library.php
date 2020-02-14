<?php
function __e($txt,$len=0){
	$output = '';
	if($len>0){
		$txt = strip_tags(trim($txt));
		$e = explode(' ',$txt);
		for($i=0;$i<=$len;$i++) $output .= ' '.$e[$i];
		$output = trim($output);
		if(strlen($txt)>strlen($output)) $output .= '...';
	}else{
		$output = $txt;
	}
	echo $output;
}

function ringkasan($text,$len=50){
	$text = strip_tags($text);
	$text = str_replace(array('&nbsp;','  '),' ',$text);
	$eText = explode(' ',$text);
	$nText = count($eText);
	$output = '';
	if($nText>$len){
		for($i=0;$i<=$len;$i++)$output[] = $eText[$i];
		$output = implode(' ',$output).'...';
	}else{
		$output = $text;
	}
	$output = trim($output);
	$output = str_replace("\n",'',$output);
	$output = str_replace("\t",' ',$output);
	$output = str_replace("  ",' ',$output);
	return $output;
}

function debug($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

function random($len=6,$type='alnum'){
	$nocut = array('md5','sha1','unique');
	if($type=='alpha')
		$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	elseif($type=='hexa')
		$str = 'abcdef0123456789';
	elseif($type=='numeric')
		$str = '0123456789';
	elseif($type=='nozero')
		$str = '123456789';
	elseif($type=='basic')
		$str = mt_rand();
	elseif($type=='md5' || $type=='unique')
		$str = md5(uniqid(mt_rand()));
	elseif($type=='sha1')
		$str = sha1(uniqid(mt_rand(), TRUE));
	else
		$str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$kode = !in_array($type, $nocut) ? substr(str_shuffle($str),0, $len) : str_shuffle($str);
	return $kode;
}

function format_angka($input, $desimal=0){
	$e = explode('.', $input);
	$angka = $e[0]+0;
	$koma = $e[1]+0;
	if($desimal==='auto'){
		$e = explode('.', $input);
		$e[1] = rtrim($e[1],'0');
		if($e[1]>0)
			$output = $e[0].','.$e[1];
		else
			$output = $e[0];
	}else{
		$output = number_format($input, $desimal, ',','.');
	}
	return $output;
}


function byte_format($num, $desimal=NULL){
	$e = explode('.', $num);
	$desimal = $desimal>0 ? $desimal : strlen($e[1]);
	if($num>=1000000000000){
		$num = round($num/1099511627776, $desimal);
		$unit = 'TB';
	}elseif($num>=1000000000){
		$num = round($num/1073741824, $desimal);
		$unit = 'GB';
	}elseif($num>=1000000){
		$num = round($num/1048576, $desimal);
		$unit = 'MB';
	}elseif($num>=1000){
		$num = round($num/1024, $desimal);
		$unit = 'KB';
	}else{
		$unit = 'Byte';
	}
	return number_format($num, $desimal, ',','.').' '.$unit;
}

function tanggal($format,$input=NULL,$type='masehi'){
	$long_bulan_en  = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$short_bulan_en = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	$long_bulan_id  = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	$short_bulan_id = array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Des');
	$bulan_en = array_merge($long_bulan_en,$short_bulan_en);
	$bulan_id = array_merge($long_bulan_id,$short_bulan_id);
	$long_hari_en  = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$short_hari_en = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
	$long_hari_id  = array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
	$short_hari_id = array('Mgu','Sen','Sel','Rab','Kam','Jum','Sab');
	$hari_en = array_merge($long_hari_en,$short_hari_en);
	$hari_id = array_merge($long_hari_id,$short_hari_id);
	$input = $input ? $input : time();
	$input = !is_numeric($input) ? strtotime($input) : $input;
	if($format=='tgl')
		$format='j F Y';
	elseif($format=='jam')
		$format='H:i';
	elseif($format=='full')
		$format='j F Y \j\a\m H:i';
	else
		$format=$format;
	$output = date($format,$input);
	$output = str_replace($bulan_en,$bulan_id,$output);
	$output = str_replace($hari_en,$hari_id,$output);
	$bulan_masehi  = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	$bulan_hijri = array(1=>'Muharram', 'Safar', 'Rabiul Awwal', 'Rabiul Akhir','Jumadil Awwal','Jumadil Akhir', 'Rajab', 'Sya\'ban', 'Ramadhan','Syawwal', 'Zulqaidah', 'Zulhijjah');
	$bulan_jawa = array(1=>'Suro','Sapar','Mulud','Ba\'da Mulud','Jumadil Awal','Jumadil Akhir','Rejeb','Ruwah','Poso','Syawal','Dulkaidah','Besar');
	$ei = explode('-',date('Y-m-d',$input));
	$julian = GregorianToJD($ei[1], $ei[2], $ei[0]);
	if($julian>=1937808 && $julian<=536838867){
		$date = cal_from_jd($julian, CAL_GREGORIAN);
		$d = $date['day']; $m = $date['month']-1; $y = $date['year'];
		$mPart =($m-13)/12;
		$jd = intPart((1461*($y+4800+intPart($mPart)))/4)+intPart((367*($m-1-12*(intPart($mPart))))/12)-intPart((3*(intPart(($y+4900+intPart($mPart))/100)))/4)+$d-32075;
		$l = $jd-1948440+10632;
		$n = intPart(($l-1)/10631);
		$l = $l-10631*$n+354;
		$j =(intPart((10985-$l)/5316))*(intPart((50*$l)/17719))+(intPart($l/5670))*(intPart((43*$l)/15238));
		$l = $l-(intPart((30-$j)/15))*(intPart((17719*$j)/50))-(intPart($j/16))*(intPart((15238*$j)/43))+29;
		$m = intPart((24*$l)/709);
		$d = $l-intPart((709*$m)/24);
		$y = 30*$n+$j-30;
		$yj = $y+512;
		$h =($julian+3)%5;
		$tglm =(int)$ei[2];
		$blnm =(int)$ei[1];
		$thnm =(int)$ei[0];
		if($julian<=1948439) $y–;
	}
	$type = strtolower(trim($type));
	if($type=='jawa')
		$output = "$d $bulan_jawa[$m] $yj";
	elseif($type=='hijri' || $type=='hijriyah')
		$output = "$d $bulan_hijri[$m] $y";
	return $output;
}

function hari($input=NULL,$type=NULL){
	$input = $input ? date('Y-m-d',strtotime($input)) : date('Y-m-d');
	$hari_masehi=array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
	$hari_jawa = array('Pon','Wage','Kliwon','Legi','Pahing');
	$hari_hijri = array('Al-Ahad','Al-Itsnayna','Ats-Tsalatsa','Al-Arba\'a','Al-Hamis','Al-Jum\'a','As-Sabt');
	$ei = explode('-',$input);
	$julian = GregorianToJD($ei[1], $ei[2], $ei[0]);
	$hm = date('w',strtotime($input));
	$hj =($julian+3)%5;
	if($type=='jawa')
		$output = $hari_jawa[$hj];
	elseif($type=='hijri')
		$output = $hari_hijri[$hm];
	else
		$output = $hari_masehi[$hm];
	return $output;
}

function intPart($floatNum){
	return($floatNum<-0.0000001? ceil($floatNum-0.0000001) : floor($floatNum+0.0000001));
}

function durasi($datefrom, $end=NULL){
	$timestamp = is_numeric($datefrom) ? $datefrom : strtotime($datefrom);
	$current_time = $end ?(is_numeric($end) ? $end : strtotime($end)) : time();
	$diff = $current_time - $timestamp;
	$intervals = array('year'=>31556926, 'month'=>2629744, 'week'=>604800, 'day'=>86400, 'hour'=>3600, 'minute'=>60);
	if($diff<60){
		$rntval = $diff <= 5 ? 'baru saja' : $diff . ' detik yang lalu';
	}      
	if($diff>=60 && $diff<$intervals['hour']){
		$diff = floor($diff/$intervals['minute']);
		$rntval = $diff.' menit yang lalu';
	}      
	if($diff>=$intervals['hour'] && $diff<$intervals['day']){
		$diff = floor($diff/$intervals['hour']);
		$rntval = $diff.' jam yang lalu';
	}  
	if($diff>=$intervals['day'] && $diff<$intervals['week']){
		$diff = floor($diff/$intervals['day']);
		$rntval = $diff == 1 ? 'kemarin' : $diff.' hari yang lalu';
	}  
	if($diff>=$intervals['week'] && $diff<$intervals['month']){
		$diff = floor($diff/$intervals['week']);
		$rntval = $diff.' minggu yang lalu';
	}  
	if($diff>=$intervals['month'] && $diff<$intervals['year']){
		$diff = floor($diff/$intervals['month']);
		$rntval = $diff.' bulan yang lalu';
	}  
	if($diff>=$intervals['year']){
		$diff = floor($diff/$intervals['year']);
		$rntval = $diff.' tahun yang lalu';
	}
	return $rntval;
}

function makeID($number,$pre=31788){
	$mult = 4;
	$lpre = strlen($pre);
	return $pre.$number*$mult;
}

function getID($number,$pre=31788){
	$mult = 4;
	$lpre = strlen($pre);
	return substr_replace($number,'',0,$lpre)/$mult;
}

function encode($string){
	$data = base64_encode($string);
	$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
	return $data;
}

function decode($string){
	$data = str_replace(array('-', '_'), array('+', '/'), $string);
	$mod4 = strlen($data) % 4;
	if($mod4){
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

function str2hex($string){
	$hex='';
	for($i=0;$i<strlen($string);$i++){
		$hex.=dechex(ord($string[$i]));
	}
	return $hex;
}

function hex2str($hex){
	$string='';
	for($i=0;$i<strlen($hex)-1;$i+=2){
		$string.=chr(hexdec($hex[$i].$hex[$i+1]));
	}
	return $string;
}

function num2str($number){
	$charset = array(32=>' ',33=>'!',34=>'"',35=>'#',36=>'$',37=>'%',38=>'&',39=>"'",40=>'(',41=>')',42=>'*',43=>'+',44=>',',45=>'-',46=>'.',47=>'/',48=>'0',49=>'1',50=>'2',51=>'3',52=>'4',53=>'5',54=>'6',55=>'7',56=>'8',57=>'9',58=>':',59=>';',60=>'<',61=>'=',62=>'>',63=>'?',64=>'@',65=>'A',66=>'B',67=>'C',68=>'D',69=>'E',70=>'F',71=>'G',72=>'H',73=>'I',74=>'J',75=>'K',76=>'L',77=>'M',78=>'N',79=>'O',80=>'P',81=>'Q',82=>'R',83=>'S',84=>'T',85=>'U',86=>'V',87=>'W',88=>'X',89=>'Y',90=>'Z',91=>'[',92=>'\\',93=>']',94=>'^',95=>'_',96=>'`',97=>'a',98=>'b',99=>'c',100=>'d',101=>'e',102=>'f',103=>'g',104=>'h',105=>'i',106=>'j',107=>'k',108=>'l',109=>'m',110=>'n',111=>'o',112=>'p',113=>'q',114=>'r',115=>'s',116=>'t',117=>'u',118=>'v',119=>'w',120=>'x',121=>'y',122=>'z',123=>'{',124=>'|',125=>'}');
	$string = '';
	while($number){
		$value = substr($number, 0, 2);
		$number = substr($number, 2);
		if($value < 32){
			$value .= substr($number, 0, 1);
			$number = substr($number, 1);
		}
		$string .= $charset[(int) $value];
	}
	return $string;
}

function str2num($string){
	$number = '';
	foreach(str_split($string) as $char) $number .= ord($char);
	return $number;
}

function array_sort_by_key(&$array, $subfield,$order=SORT_ASC){
	$sortarray = array();
	foreach($array as $key => $row){
		$sortarray[$key] = $row[$subfield];
	}
	array_multisort($sortarray, $order, $array);
}

function ajn_encrypt($v){
	$k = 'nDBkcXeXM88aYs7';
	if($v==''){
		return false;
	}
	$g = strlen($k)-1;
	$e = strlen($v);
	for($i = 0; $i < $e; $i++){
		$j = $i;
		while($j > $g){
			$j = $j - $g;
		}
		$h = ord($v[$i]);
		$l = ord($k[$j]);
		$f = $h + $l + $i - $j;
		if($f >= 256){
			$b[$i] = chr($f - 255);
		}else{
			$b[$i] = chr($f);
		}
	}
	$b = implode($b);
	return $b;
}

function ajn_decrypt($v){
	$k = 'nDBkcXeXM88aYs7';
	if($v==''){
		return false;
	}
	$g = strlen($k)-1;
	$e = strlen($v);
	for($i = 0; $i < $e; $i++){
		$j = $i;
		while($j > $g){
			$j = $j - $g;
		}
		$h = ord($v[$i]);
		$l = ord($k[$j]);
		$f = $h - $l - $i + $j;
		if($f < 0){
			$b[$i] = chr($f + 255);
		}else{
			$b[$i] = chr($f);
		}
	}
	$b = implode($b);
	return $b;
}

function refresh_filename($file){
	$ext = strtolower(strrchr($file, '.'));
	$file_name = basename($file);
	$dir_name = dirname($file).'/';
	$name = basename($file, $ext);
	$appendix = str_replace('-','',strrchr($name, '-'));
	$basename = $appendix>0 ? substr_replace($name, '', strpos($name,$appendix)-1, strlen($appendix)+1) : $name;
	$count = $appendix>0 ? $appendix : 0;
	$file_name = $file;
	while(file_exists($file_name)){
		$count ++;
		if(is_file($dir_name.$basename.'-'.$count.$ext)){
			$count++;
			$file_name = $dir_name.$basename.'-'.$count.$ext;
		}
		else{
			$file_name = $dir_name.$basename.'-'.$count.$ext;
		}
	}
	return basename($file_name);
}

function paging($total, $per_page = 10, $adjacents=2){
	global $limit;
	$per_page = $limit>0 ? $limit :($per_page!=10 ? $per_page : 10);
	$per_page = $per_page;
	$total =(is_array($total) && $total['total']>=0) ? $total['total'] :($total>0 ? $total : 0);
	if($total<1) return false;
	$page = $_GET['page']>1 ? $_GET['page'] : 1;
	$start =($page - 1) * $per_page;
	$prev = $page - 1;
	$next = $page + 1;
	$lastpage = ceil($total/$per_page);
	$lpm1 = $lastpage - 1;
	$paging = "";
	$uri = explode('?', $_SERVER['REQUEST_URI']);
	if($uri[1]){
		parse_str($uri[1], $urls);
		unset($urls['page']);
		unset($urls['ajax']);
		unset($urls['_']);
	}
	$url = $uri[0];
	$url .= $urls ? '?'.http_build_query($urls).'&' : '?';
	if($lastpage > 1){
		$paging .= "<ul class=\"pager\">";
		if($lastpage < 7 +($adjacents * 2)){
			for($counter = 1; $counter <= $lastpage; $counter++){
				if($counter == $page)
					$paging.= "<li><a class=\"current\">$counter</a></li>";
				else
					$paging.= "<li><a href=\"{$url}page=$counter\">$counter</a></li>";
			}
		}
		elseif($lastpage > 5 +($adjacents * 2)){
			if($page < 1 +($adjacents * 2)){
				for($counter = 1; $counter < 4 +($adjacents * 2); $counter++){
					if($counter == $page)
						$paging.= "<li><a class=\"current\">$counter</a></li>";
					else
						$paging.= "<li><a href=\"{$url}page=$counter\">$counter</a></li>";
				}
				$paging.= "<li class=\"dot\">...</li>";
				$paging.= "<li><a href=\"{$url}page=$lpm1\">$lpm1</a></li>";
				$paging.= "<li><a href=\"{$url}page=$lastpage\">$lastpage</a></li>";
			}
			elseif($lastpage -($adjacents * 2) > $page && $page >($adjacents * 2)){
				$paging.= "<li><a href=\"{$url}page=1\">1</a></li>";
				$paging.= "<li><a href=\"{$url}page=2\">2</a></li>";
				$paging.= "<li class=\"dot\">...</li>";
				for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
					if($counter == $page)
						$paging.= "<li><a class=\"current\">$counter</a></li>";
					else
						$paging.= "<li><a href=\"{$url}page=$counter\">$counter</a></li>";
				}
				$paging.= "<li class=\"dot\">...</li>";
				$paging.= "<li><a href=\"{$url}page=$lpm1\">$lpm1</a></li>";
				$paging.= "<li><a href=\"{$url}page=$lastpage\">$lastpage</a></li>";
			}
			else{
				$paging.= "<li><a href=\"{$url}page=1\">1</a></li>";
				$paging.= "<li><a href=\"{$url}page=2\">2</a></li>";
				$paging.= "<li class=\"dot\">...</li>";
				for($counter = $lastpage -(2 +($adjacents * 2)); $counter <= $lastpage; $counter++){
					if($counter == $page)
						$paging.= "<li><a class=\"current\">$counter</a></li>";
					else
						$paging.= "<li><a href=\"{$url}page=$counter\">$counter</a></li>";
				}
			}
		}
		$paging.= "</ul>\n";
	}
	echo $paging;
}

function time_to_sec($waktu){
	list($h, $m, $s) = explode(':', $waktu); 
	return($h * 3600) +($m * 60) + $s; 
}

function sec_to_time($detik){ 
	$h = floor($detik / 3600); 
	$m = floor(($detik % 3600) / 60); 
	$s = $detik -($h * 3600) -($m * 60); 
	$output = sprintf('%02d:%02d:%02d', $h, $m, $s); 
	return $output;
}

function getDates($sDate, $eDate=NULL, $formatOutput=NULL, $exclude=NULL){
	$tgl_libur = $exclude['tgl_libur'] ? $exclude['tgl_libur'] : array();
	$hari_libur = $exclude['hari_libur'] ? $exclude['hari_libur'] : array();
	$format = $formatOutput ? $formatOutput : "Y-m-d";
	$eDate = $eDate ? $eDate : date('Y-m-d');
	$day = 86400;
	if(strtotime($sDate)>strtotime($eDate)){
		$startDate = strtotime($eDate);
		$endDate = strtotime($sDate);
	}else{
		$startDate = strtotime($sDate);
		$endDate = strtotime($eDate);
	}
	$numDays = round(($endDate - $startDate) / $day) + 1;
	$days = array();
	for($i = 0; $i < $numDays; $i++){
		$tgl = date($format,($startDate +($i * $day)));
		$hari = date('w',($startDate +($i * $day)));
		if(in_array($tgl, $tgl_libur) || in_array($hari,$hari_libur))
			$days[] = '';
		else
			$days[] = $tgl;
	}
	return array_values(array_filter($days)); 
}

function getMonths($sDate, $eDate=null,$sep='-'){
	$sDate = strlen($sDate)==7 ? $sDate.'-01' : $sDate;
	$eDate = $eDate==null ? date('Y-m-d') :(strlen($eDate)==7 ? $eDate.'-01' : $eDate);
	$months = array();
	while(strtotime($sDate) <= strtotime($eDate)){
		$months[] = date('Y'.$sep.'m', strtotime($sDate));
		$sDate = date('Y-m-d', strtotime($sDate.'+ 1 month'));
	}
	return $months;
}

function is_mobile($agent=''){
	if($agent=='') $agent = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/android|mobile|avantgo|bada\/|blackberry|rim|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|hp-tablet|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm(os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|bolt|windows(ce|phone)|xda|xiino/i',$agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s)|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp(i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac(|\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt(|\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg(g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v)|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v)|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-|)|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($agent,0,4)))
		return true;
	else
		return false;
}

function client($u_agent = null){
	if(is_null($u_agent)){
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
	}
	$platform = null;
	$browser  = null;
	$version  = null;
	$empty = array('platform' => $platform, 'browser' => $browser, 'version' => $version);
	if(!$u_agent) return $empty;
	if(preg_match('/\((.*?)\)/im', $u_agent, $parent_matches)){
		preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\)?Nintendo\(WiiU?|3?DS)|Xbox(\ One)?)
		(?:\ [^;]*)?(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);
		$priority = array('Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'CrOS', 'Linux', 'X11');
		$result['platform'] = array_unique($result['platform']);
		if(count($result['platform']) > 1){
			if($keys = array_intersect($priority, $result['platform'])){
				$platform = reset($keys);
			}else{
				$platform = $result['platform'][0];
			}
		}elseif(isset($result['platform'][0])){
			$platform = $result['platform'][0];
		}
	}
	if($platform == 'linux-gnu' || $platform == 'X11'){
		$platform = 'Linux';
	}elseif($platform == 'CrOS'){
		$platform = 'Chrome OS';
	}
	preg_match_all('%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|Chrome|	Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|CriOS|	Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|Valve\ Steam\ Tenfoot|NintendoBrowser|PLAYSTATION\(\d|Vita)+)(?:\)?;?)(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',	$u_agent, $result, PREG_PATTERN_ORDER);
	if(!isset($result['browser'][0]) || !isset($result['version'][0])){
		if(preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result)){
			return array('platform' => $platform ?: null, 'browser' => $result['browser'], 'version' => isset($result['version']) ? $result['version'] ?: null : null);
		}
		return $empty;
		}
	if(preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $u_agent, $rv_result)){
		$rv_result = $rv_result['version'];
	}
	$browser = $result['browser'][0];
	$version = $result['version'][0];
	$lowerBrowser = array_map('strtolower', $result['browser']);
	$find = function($search, &$key) use($lowerBrowser){
		$xkey = array_search(strtolower($search), $lowerBrowser);
		if($xkey !== false){
			$key = $xkey;
			return true;
		}
		return false;
	};
	$key  = 0;
	$ekey = 0;
	if($browser=='Iceweasel'){
		$browser = 'Firefox';
	}elseif($find('Playstation Vita', $key)){
		$platform = 'PlayStation Vita';
		$browser  = 'Browser';
	}elseif($find('Kindle Fire', $key) || $find('Silk', $key)){
		$browser  = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
		$platform = 'Kindle Fire';
	if(!($version = $result['version'][$key]) || !is_numeric($version[0])){
		$version = $result['version'][array_search('Version', $result['browser'])];
	}
	}elseif($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS'){
		$browser = 'NintendoBrowser';
		$version = $result['version'][$key];
	}elseif($find('Kindle', $key)){
		$browser  = $result['browser'][$key];
		$platform = 'Kindle';
		$version  = $result['version'][$key];
	}elseif($find('OPR', $key)){
		$browser = 'Opera';
		$version = $result['version'][$key];
	}elseif($find('Opera', $key)){
		$browser = 'Opera';
		$find('Version', $key);
		$version = $result['version'][$key];
	}elseif($find('Midori', $key)){
		$browser = 'Midori';
		$version = $result['version'][$key];
	}elseif($browser == 'MSIE' || ($rv_result && $find('Trident', $key)) || $find('Edge', $ekey)){
		$browser = 'MSIE';
		if($find('IEMobile', $key)){
			$browser = 'IEMobile';
			$version = $result['version'][$key];
		}elseif($ekey){
			$version = $result['version'][$ekey];
		}else{
			$version = $rv_result ?: $result['version'][$key];
		}
		if(version_compare($version, '12', '>=')){
			$browser = 'Microsoft Edge';
		}
	}elseif($find('Vivaldi', $key)){
		$browser = 'Vivaldi';
		$version = $result['version'][$key];
	}elseif($find('Valve Steam Tenfoot', $key)){
		$browser = 'Valve Steam Tenfoot';
		$version = $result['version'][$key];
	}elseif($find('Chrome', $key) || $find('CriOS', $key)){
		$browser = 'Chrome';
		$version = $result['version'][$key];
	}elseif($browser == 'AppleWebKit'){
		if(($platform == 'Android' && !($key = 0))){
			$browser = 'Android Browser';
		}elseif(strpos($platform, 'BB') === 0){
			$browser  = 'BlackBerry Browser';
			$platform = 'BlackBerry';
		}elseif($platform == 'BlackBerry' || $platform == 'PlayBook'){
			$browser = 'BlackBerry Browser';
		}elseif($find('Safari', $key)){
			$browser = 'Safari';
		}elseif($find('TizenBrowser', $key)){
			$browser = 'TizenBrowser';
		}
		$find('Version', $key);
		$version = $result['version'][$key];
	}elseif($key = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser']))){
		$key = reset($key);
		$platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $key);
		$browser  = 'NetFront';
	}
	return array('platform' => $platform ?: null, 'browser' => $browser ?: null, 'version' => $version ?: null);
}

function array2xml($array){
	$xml = '';
	//$array = count($array)>1 ? array('items'=>$array) : $array;
	foreach($array as $element => $value){
		$element = is_int($element) ? 'item' : $element;
		if(is_array($value)){
			$xml .= "<$element>\n".array2xml($value)."</$element>\n";
		}elseif($value == ''){
			$xml .= "<$element />\n";
		}else{
			$xml .= "\t<$element>".htmlentities($value)."</$element>\n";
		}
	}
	return $xml;
}


function array2json($array, $pretty=false){
	if($pretty)
		return prettyPrint(json_encode($array));
	else
		return json_encode($array);
}



function prettyPrint($json){
	$result = '';
	$level = 0;
	$in_quotes = false;
	$in_escape = false;
	$ends_line_level = NULL;
	$json_length = strlen($json);
	for($i = 0; $i < $json_length; $i++){
		$char = $json[$i];
		$new_line_level = NULL;
		$post = "";
		if($ends_line_level !== NULL){
			$new_line_level = $ends_line_level;
			$ends_line_level = NULL;
		}
		if($in_escape){
			$in_escape = false;
		}else if($char === '"'){
			$in_quotes = !$in_quotes;
		}else if(! $in_quotes){
			switch($char){
				case '}': case ']':
					$level--;
					$ends_line_level = NULL;
					$new_line_level = $level;
					break;
				case '{': case '[':
					$level++;
				case ',':
					$ends_line_level = $level;
					break;
				case ':':
					$post = " ";
					break;

				case " ": case "\t": case "\n": case "\r":
					$char = "";
					$ends_line_level = $new_line_level;
					$new_line_level = NULL;
					break;
			}
		}else if($char === '\\'){
			$in_escape = true;
		}
		if($new_line_level !== NULL){
			$result .= "\n".str_repeat("\t", $new_line_level);
		}
		$result .= $char.$post;
	}
	return $result;
}

function embed_video($url,$wh='640x360'){
	$embed = '';
	$wh = explode('x',$wh);
	$width = $wh[0];
	$height = $wh[1];
	if(strpos($url, 'youtube.com') || strpos($url, 'www.youtube.com') || strpos($url, 'youtu.be')){
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $ytID);
		$ytID = $ytID[1];
		$video_url= 'https://www.youtube.com/embed/'.$ytID.'?rel=0&autoplay=0&modestbranding=1&cc_load_policy=1&iv_load_policy=3';
	}elseif(strpos($url, 'facebook.com') || strpos($url, 'www.facebook.com')){
		$video_url= 'https://www.facebook.com/plugins/video.php?href='.urlencode($url).'&show_text=0&width='.$width.'&height='.$height;
	}elseif(stripos($url,'vimeo.com') || stripos($url,'www.vimeo.com')){
		$vimID = explode('vimeo.com/',$url)[1];
		$video_url= 'https://player.vimeo.com/video/'.$vimID;
	}elseif(stripos($url,'dailymotion.com') || stripos($url,'www.dailymotion.com')){
		$dmID = explode('dailymotion.com/video/',$url)[1];
		$video_url= 'https://www.dailymotion.com/embed/video/'.$dmID.'?autoplay=0&info=0&logo=0&related=0&social=0';
	}elseif(stripos($url,'metacafe.com') || stripos($url,'www.metacafe.com')){
		$mcID = explode('metacafe.com/watch/',$url)[1];
		$video_url= 'http://www.metacafe.com/embed/'.$mcID;
	}else{
		$video_url = '';
	}
	if($video_url){
		$embed = '<div class="video"><img class="video-ratio" src="http://placehold.it/16x9"/><iframe width="'.$width.'" height="'.$height.'" src="'.$video_url.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
	}
	return $embed;
}

function hex2rgb($hex, $alpha = false){
	$hex = str_replace('#', '', $hex);
	if(strlen($hex)==6){
		$rgb['r'] = hexdec(substr($hex, 0, 2));
		$rgb['g'] = hexdec(substr($hex, 2, 2));
		$rgb['b'] = hexdec(substr($hex, 4, 2));
	}elseif(strlen($hex)==3){
		$rgb['r'] = hexdec(str_repeat(substr($hex, 0, 1), 2));
		$rgb['g'] = hexdec(str_repeat(substr($hex, 1, 1), 2));
		$rgb['b'] = hexdec(str_repeat(substr($hex, 2, 1), 2));
	}else{
		$rgb['r'] = '0';
		$rgb['g'] = '0';
		$rgb['b'] = '0';
	}
	if($alpha){
		$rgb['a'] = $alpha;
	}
	return $rgb;
}

function rgb2hex($r, $g=-1, $b=-1){
	if(is_array($r) && sizeof($r) == 3)
	list($r, $g, $b) = $r;
	$r = intval($r);
	$g = intval($g);
	$b = intval($b);
	$r = dechex($r<0?0:($r>255?255:$r));
	$g = dechex($g<0?0:($g>255?255:$g));
	$b = dechex($b<0?0:($b>255?255:$b));

	$color =(strlen($r)<2 ? '0' : '').$r;
	$color .=(strlen($g)<2 ? '0' : '').$g;
	$color .=(strlen($b)<2 ? '0' : '').$b;
	return '#'.$color;
}

function getDateWeek($year=0, $week=0){
	if($week==0) $week = date('W');
	if($year==0) $year = date('Y');
	$date['start'] = date("Y-m-d", strtotime($year."W".$week."0"));
	$date['end'] = date("Y-m-d", strtotime($year."W".$week."6"));
	return $date;
}
