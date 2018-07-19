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
<!-- <header>
<h1>AJN Framework</h1>
<ul>
	<li><a href="<?php echo SITE_URI ?>"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="#"><i class="fa fa-file"></i> Framework</a>
	<ul>
		<li><a href="<?php echo SITE_URI ?>page/framework">Basic AJN Framework</a></li>
		<li><a href="<?php echo SITE_URI ?>page/module">Module</a></li>
		<li><a href="<?php echo SITE_URI ?>page/slug">URI & Slug</a></li>
		<li><a href="<?php echo SITE_URI ?>page/extension.html">Extension</a></li>
		<li><a href="<?php echo SITE_URI ?>page/library">Library & Plugin</a></li>
	</ul></li>
	<li><a href="#"><i class="fa fa-server"></i> Web Service</a>
	<ul>
		<li><a href="<?php echo SITE_URI ?>page/json-xml">Web Service: JSON & XML</a></li>
		<li><a href="<?php echo SITE_URI ?>page/xls">Web Service: XLS</a></li>
		<li><a href="<?php echo SITE_URI ?>page/webservice-lainnya">Web Service: Lainnya</a></li>
	</ul></li>
	<li><a href="<?php echo SITE_URI ?>page/template"><i class="fa fa-html5"></i> Template</a></li>
	<li><a href="<?php echo SITE_URI ?>page/database"><i class="fa fa-database"></i> Database</a></li>
</ul>
</header> -->
<nav class="navbar navbar-menu animated fadeInDown navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
			<a class="navbar-brand" href="<?php echo SITE_URI ?>"><?php echo $setting['site_name'] ?></a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo SITE_URI ?>">Home</a></li>
				<li><a href="<?php echo SITE_URI ?>about.html">About</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Framework <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo SITE_URI ?>page/framework">Basic AJN Framework</a></li>
					<li><a href="<?php echo SITE_URI ?>page/module">Module</a></li>
					<li><a href="<?php echo SITE_URI ?>page/slug">URI & Slug</a></li>
					<li><a href="<?php echo SITE_URI ?>page/extension.html">Extension</a></li>
					<li><a href="<?php echo SITE_URI ?>page/library">Library & Plugin</a></li>
				</ul>
				</li>
            	<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Web Service <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo SITE_URI ?>page/json-xml">Web Service: JSON & XML</a></li>
					<li><a href="<?php echo SITE_URI ?>page/xls">Web Service: XLS</a></li>
					<li><a href="<?php echo SITE_URI ?>page/webservice-lainnya">Web Service: Lainnya</a></li>
				</ul></li>
				<li><a href="<?php echo SITE_URI ?>contact.html">Contact</a></li>
			</ul>
		</div>
	</div>
</nav>
<div class="container">
