<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $site['title'] ? $site['title'].' - ' : '' ?>Administrator Panel</title>
    <link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/fonts-icons.min.css" />
    <!-- <link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/styles.min.css" /> -->
    <link rel="icon" href="<?php echo SITE_URI ?>favicon.ico" />
	<!--[if IE]>
	<script>(function(){var a=("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(",");for(var b=0;b<a.length;b++){document.createElement(a[b])}try{document.execCommand("BackgroundImageCache",false,true)}catch(c){}})();</script>
	<![endif]-->
</head>
<body class="admin-page">
<style>
.admin-page .navbar{background:#2a3b4c}
.admin-page .navbar a,.admin-page .navbar a:hover,.admin-page .navbar a:visited{color:#fff}
.admin-page .accountMenu a{text-decoration:none;display:block;color:#444!important;padding:6px 10px}
.admin-page .accountMenu a:hover{text-decoration:none;background:#eee}

body{padding-top:3.5rem}
h1{padding-bottom:9px;margin-bottom:20px;border-bottom:1px solid #eee}
.sidebar{position:fixed;top:51px;bottom:0;left:0;z-index:1000;padding:20px 0;overflow-x:hidden;overflow-y:auto;border-right:1px solid #eee}
.sidebar .nav{margin-bottom:20px}
.sidebar .nav-item{width:100%}
.sidebar .nav-item .item-sub .nav-link{padding-left:30px}
/*.sidebar .nav-item.item-head>a{font-weight:bold}*/
.sidebar .nav-item a{color:#333}
.sidebar .nav-item+.nav-item{margin-left:0}
.sidebar .nav-link{border-radius:0}
.sidebar .nav ul{margin:0;padding:0;list-style:none}
.placeholders{padding-bottom:3rem}
.placeholder img{padding-top:1.5rem;padding-bottom:1.5rem}
.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 60px;
  line-height: 60px;
  background-color: #f5f5f5;
}

</style>
<header>
<nav class="navbar fixed-top navbar-expand-lg">
	<a class="navbar-brand" href="#"><i class="fa fa-globe fa-2x"></i></a>
	<!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
	<span class="fa fa-bars"></span>
	</button> -->
	
	<div class="collapse navbar-collapse" id="mainMenu">
		<ul class="navbar-nav">
			<li class="nav-item active"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a></li>
			<li class="nav-item"><a class="nav-link" href="#">Features</a></li>
			<li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
			<li class="nav-item"><a class="nav-link disabled" href="#">Disabled</a></li>
		</ul>
	</div>
	<div class="navbar-nav">
	<div class="btn-group text-right">
		<a href="#" class="btn"><?php echo $_SESSION['admin_nama'] ?></a>
		<a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><i class="fa fa-cogs"></i></a>
		<div class="dropdown-menu accountMenu">
			<a href="#" class="dropdown-item">Edit Profil</a>
			<a href="#" class="dropdown-item">Ganti Password</a>
			<div class="dropdown-divider"></div>
			<a href="<?php echo ADMIN_URI ?>logout.php" class="dropdown-item">Logout</a>
		</div>
		<a href="#" class="btn d-xl-none d-sm-none d-md-none dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bars"></i></a>
		<ul class="dropdown-menu">
			<li class="nav-item active"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a></li>
			<li class="nav-item"><a class="nav-link" href="#">Features</a></li>
			<li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
			<li class="nav-item"><a class="nav-link disabled" href="#">Disabled</a></li>
		</ul>
	</button>
	</div>
	</div>
</nav>
</header>
