<?php

if($plugins){
	foreach($plugins as $pv=>$pn){
		$pl[$pv] = new $pn();
	}
}