<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $site['title'] ? $site['title'].' | ' : ''; echo SITE_NAME; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_URI ?>css/bootstrap-3.3.7.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_URI ?>css/styles.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_URI ?>css/animated.min.css" /> 
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_URI ?>css/font-icons.min.css" /> 
<link rel="shortcut icon" href="<?php echo SITE_URI ?>favicon.ico" />
<!--[if IE]>
<script>(function(){var a=("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(",");for(var b=0;b<a.length;b++){document.createElement(a[b])}try{document.execCommand("BackgroundImageCache",false,true)}catch(c){}})();</script>
<![endif]-->
<script src="<?php echo TEMPLATE_URI ?>js/jquery-1.12.4.min.js"></script>
<script src="<?php echo TEMPLATE_URI ?>js/bootstrap-3.3.7.min.js"></script>
<script src="<?php echo TEMPLATE_URI ?>js/plugins.validInput.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo SITE_URI ?>">My Project</a>
	</div>
	<div id="navbar" class="collapse navbar-collapse navbar-right">
		<ul class="nav navbar-nav">
			<li<?php echo MODULE=='home' ? ' class="active"' : '' ?>><a href="<?php echo SITE_URI ?>">Home</a></li>
			<li<?php echo (MODULE=='page' && $slug1=='about') ? ' class="active"' : '' ?>><a href="<?php echo SITE_URI ?>about.html">About</a></li>
			<li<?php echo (MODULE=='page' && $slug1=='contact') ? ' class="active"' : '' ?>><a href="<?php echo SITE_URI ?>contact.html">Contact</a></li>
		</ul>
	</div>
	</div>
</nav>
<div class="container">
