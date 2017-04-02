<?php
class combo{
    function tanggal($properties, $selected=NULL){
        if(!is_array($properties)){
           $output .= '<select name="'.$properties.'">';
        }else{
            $output .= '<select';
            foreach($properties as $key=>$val)
                 $output .= " $key=\"$val\"";
            $output .= '>';
        }
        for ($i=1; $i<=31; $i++) {
            $val=$i<10 ? ")" . $i : $i;
            if ($selected==$i)
                $sel=" selected";
            else
                $sel="";
            $output.="<option value=\"$val\"$sel>$i</option>";
        }
        $output.="</select>";
        return $output;
    }
    function bulan($properties, $selected=NULL){
        $nama_bulan=array(
            1=>"Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );
        if(!is_array($properties)){
           $output = '<select name="'.$properties.'">';
        }else{
            $output = '<select';
            foreach($properties as $key=>$val)
                 $output .= " $key=\"$val\"";
            $output .= '>';
        }

        for ($i=1; $i<=12; $i++) {
            $val=$i<10 ? "0" . $i : $i;
            if ($selected==$val || $selected==$i)
                $sel=" selected";
            else
                $sel="";
            $output.="<option value=\"$val\"$sel>$nama_bulan[$i]</option>";
        }
        $output.="</select>";
        return $output;
    }
    function angka($start, $end, $name, $zero=0, $selected=NULL){
        $output="<select name=$var>";
        for ($i=$start; $i<=$end; $i++) {
            if ($zero==1) {
                $val=$text=$i<10 ? "0" . $i : $i;
            } else {
                $val=$text=$i;
            }
            if ($i==$selected)
                $sel=" selected";
            else
                $sel="";
            $output.="<option value=\"$val\"$sel>$text</option>";
        }
        $output.="</select>";
        return $output;
    }
    function text($txt_array, $name, $selected=NULL,$pre=NULL){
        $array =$txt_array;
		$array_key = array_keys($array);
		$sum_array = array_sum($array_key);
        $n     =count($array);
        $output="<select name=\"$name\">";
		if($pre)
			$output.="<option value=\"\">$pre</option>";
		if($array_key[0]!=0){
		foreach($array as $key=>$val){
			if ($key==$selected)
                $sel=" selected";
            else
                $sel="";
            $output.="<option value=\"$key\"$sel>$val</option>";
		}
		}else{
        for ($i=0; $i<=$n-1; $i++) {
            if ($array[$i]==$selected)
                $sel=" selected";
            else
                $sel="";
            $output.="<option value=\"$array[$i]\"$sel>$array[$i]</option>";
        }
		}
        $output.="</select>";
        return  $output;
    }

    public function combo($properties=null, $data=null, $curr=null){
        $str = '';
        if(is_array($properties)){
                $str = '<select';
                foreach($properties as $key=>$val)
                    $str .= " $key=\"$val\"";
                $str .= '>';
        }else{
                $str = '<select name="'.$properties.'">';
        }
        
                if(is_array($data) && $data['min']>=0 && $data['max']){
                    for($i=$data['min'];$i<=$data['max'];$i++){
                        $sel = $i==$curr ? ' selected' : '';
                        $str .= "<option value=\"$i\"'.$sel.'>$i</option>";
                    }
                }elseif($data=='tgl'){
                    for($i=1;$i<=31;$i++){
                        $val = $i<10 ? "0$i" : $i;
                        $sel = $val==$curr ? ' selected' : '';
                        $str .= '<option value="'.$val.'"'.$sel.'>$i</option>';
                    }
                }elseif($data=='bln' || $data=='bln1' || $data=='bln2'){
                    $nm = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                    for($i=1;$i<=12;$i++){
                        $val = $i<10 ? "0$i" : $i;
                        $sel = $val==$curr ? ' selected' : '';
                        $str .= '<option value="'.($i<10 ? "0$i" : $i).'"'.$sel.'>'.$nm[$i].'</option>';
                    }
                }elseif(is_array($data) && count($data)>=1){
                    foreach($data as $key=>$val){
                       // $key = is_numeric($key) ? $val : $key;
                        $sel = $key==$curr ? ' selected' : '';
                        $str .= "<option value=\"$key\"$sel>$val</option>";
                    }
                }
                $str .= '</select>';
            return $str;
    }
}

