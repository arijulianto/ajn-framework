<!DOCTYPE HTML>
<html>
<head>
<title><?php echo isset($site['title']) ? $site['title'].' - ' : '' ?>Administrator Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Web Admin Template" />
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/bootstrap-3.3.4.min.css"  type="text/css" />
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/style.min.css"  type="text/css" />
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/fonts-icons.min.css" /> 
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/jquery-ui-1.12.1.min.css" /> 
<link rel="shortcut icon" href="<?php echo SITE_URI ?>favicon.ico" />
<!--[if IE]>
<script>(function(){var a=("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(",");for(var b=0;b<a.length;b++){document.createElement(a[b])}try{document.execCommand("BackgroundImageCache",false,true)}catch(c){}})();</script>
<![endif]-->
<script src="<?php echo ADMIN_URI ?>scripts/jquery-1.11.2.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/plugins.validInput.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/bootstrap-3.3.4.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/jquery-ui-1.12.1.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/metisMenu.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/tinymce.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/custom.min.js"></script>
</head>
<body>
<div id="wrapper">
    <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo ADMIN_URI ?>index.php"><img src="<?php echo SITE_URI ?>media/images/logo.png" style="height:50px;float:left;margin-top:-14px;margin-right:10px" class="hidden-xs" />Administrator Panel</a>
        </div>
        <ul class="nav navbar-nav navbar-right hidden-xs">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><i class="fa fa-gear"></i></a>
                <ul class="dropdown-menu">
                    <li class="m_2">Hallo <strong><?php echo $_SESSION['admin_nama'] ?></strong></li>
                    <li class="m_2"><a href="<?php echo SITE_URI ?>" target="_blank"><i class="fa fa-globe"></i> Lihat Website</a></li>
<?php if(is_string($adm['login_source'])){ ?>
                    <li class="m_2"><a href="<?php echo ADMIN_URI ?>account.php"><i class="fa fa-user"></i> Profile</a></li>
                    <li class="m_2"><a href="<?php echo ADMIN_URI ?>account.php/password"><i class="fa fa-usd"></i> Ganti Password</a></li>
<?php } ?>
                    <li class="m_2"><a href="<?php echo ADMIN_URI ?>logout.php"><i class="fa fa-lock"></i> Logout</a></li>   
                </ul>
            </li>
        </ul>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li><a href="<?php echo ADMIN_URI ?>index.php"<?php echo (MODULE=='home' || MODULE=='index') ? ' class="current"' : '' ?>><i class="fa fa-home nav_icon"></i>Dashboard</a></li>
<?php
if($menu['nav']){
foreach($menu['nav'] as $link=>$mnu){
    $links = explode('/', $link);
    if(substr($link,0,1)=='#'){
        $md = '#';
    }else{
        $md = str_replace('.php','',$links[0]);
    }
    if(is_array($mnu)){
        echo "<li",($md=='account'?' class="hidden-xs"':''),"><a href=\"",ADMIN_URI,"$link\"",MODULE==$md ? ' class="current"' : '',">",(isset($mnu['icon'])?'<i class="fa fa-'.$mnu['icon'].' nav_icon"></i>':''),"$mnu[label]",(isset($mnu['menu'])?'<span class="fa arrow"></span>':''),"</a>";
        if(isset($mnu['menu'])){
            echo "<ul class=\"nav nav-second-level\">";
            foreach($mnu['menu'] as $sLink=>$sLabel){
                $e = explode('/', $sLink);
                $md = str_replace('.php','',$e[0]);
                echo "<li class=\"nav-item item-sub\"><a class=\"nav-link",((MODULE==$md && $slug1==$e[1]) ? ' current' : ''),"\" href=\"",ADMIN_URI,$sLink,"\">$sLabel</a></li>";
            }
            echo "</ul>";
        }
        echo "</li>\n";
    }else{
        echo "<li><a href=\"",ADMIN_URI,"$link\"",MODULE==$md ? ' class="current"' : '',">$mnu</a></li>\n";
    }
}
}

if($adm['menu_media']){
    echo "<li><a href=\"",ADMIN_URI,"media.php\"",MODULE=='media' ? ' class="current"' : '',"><i class=\"fa fa-image\"></i> Media Manager</a></li>\n";
}
if($adm['menu_setting']){
    echo "<li><a href=\"",ADMIN_URI,"setting.php\"",MODULE=='setting' ? ' class="current"' : '',"><i class=\"fa fa-gear\"></i> Setting",(is_array($adm['menu_setting'])?'<span class="fa arrow"></span>':''),"</a>";
    if(is_array($adm['menu_setting'])){
        echo "<ul class=\"nav nav-second-level\">";
        foreach($adm['menu_setting'] as $link=>$name){
            if($link==''){
                echo "<li class=\"nav-item item-sub\"><a class=\"nav-link\" href=\"",ADMIN_URI,"setting.php\"",(MODULE=='setting' && !$slug1) ? ' class="current"' : '',">$name</a></li>\n";
            }elseif(strpos($link, '.php')){
                $em = explode('.php',$link);
                if(isset($em[1])) $sl = explode('/',trim($em[1],'/'));
                if($sl)
                    echo "<li class=\"nav-item item-sub\"><a class=\"nav-link\" href=\"",ADMIN_URI,"$link\"",(MODULE==$em[0] && $slug1==$sl[0]) ? ' class="current"' : '',">$name</a></li>\n";
                else
                    echo "<li class=\"nav-item item-sub\"><a class=\"nav-link\" href=\"",ADMIN_URI,"$link\"",MODULE==$em[0] ? ' class="current"' : '',">$name</a></li>\n";
            }else{
                echo "<li class=\"nav-item item-sub\"><a class=\"nav-link\" href=\"",ADMIN_URI,"setting.php/$link\"",(MODULE=='setting' && $slug1==$link) ? ' class="current"' : '',">$name</a></li>\n";
            }
        }
        echo "</ul>";
    }
    echo "</li>\n";
}


if(is_string($adm['login_source'])){ ?>
                    <li class="hidden-md hidden-sm hidden-lg"><a href="<?php echo ADMIN_URI ?>account.php"><i class="fa fa-user nav_icon"></i> Profile</a></li>
                    <li class="hidden-md hidden-sm hidden-lg"><a href="<?php echo ADMIN_URI ?>account.php/password"><i class="fa fa-usd nav_icon"></i> Ganti Password</a></li>
<?php } ?>
                    <li class="hidden-md hidden-sm hidden-lg"><a href="<?php echo ADMIN_URI ?>logout.php"><i class="fa fa-lock nav_icon"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="page-wrapper">
