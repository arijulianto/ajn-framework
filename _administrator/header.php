<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $site['title'] ? $site['title'].' - ' : '' ?>Administrator Panel</title>
    <link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/fonts-icons.min.css" />
    <link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/styles.min.css" />
    <link rel="icon" href="<?php echo SITE_URI ?>favicon.ico" />
	<!--[if IE]>
	<script>(function(){var a=("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(",");for(var b=0;b<a.length;b++){document.createElement(a[b])}try{document.execCommand("BackgroundImageCache",false,true)}catch(c){}})();</script>
	<![endif]-->
</head>
<body class="t-default sidebar-is-reduced sidebar-is-expanded mobile-header mobile-header">
  <header class="l-header">
    <div class="l-header__inner clearfix">
      	<div class="c-header-icon js-hamburger">
	        <button class="hamburger-toggle">
	        	<i class="ti-menu" aria-hidden="true"></i>
	        </button>
      	</div>
		<div class="account-menu header-dropdown" data-dropdown="account-menu">
			<div class="header-icons-group">
				<div class="c-header-icon logout"><i class="fa fa-user-circle"></i></div>
			  	<p class="user-header-caption"><span class="user-header-name">Hi Indra Prasetya</span><i class="ti-angle-down"></i></p>
			  	<div class="layer-dropdown-dashboard"></div>
			</div>
			<div class="account-list-menu-dropdown header-list-dropdown" data-target-dropdown="account-menu" style="display: none">
				<ul>
					<li><a href="account_setting.html"><i class="ti-settings"></i> Account Settings</a></li>
					<li><a href="#"><i class="ti-power-off"></i> Logout</a></li>
				</ul>
		  	</div>
		</div>
    </div>
  </header>
  <div class="l-sidebar">
    <div class="logo">
      <div class="logo__img">
      	<i class="fa fa-globe fa-2x"></i>
      </div>
    </div>
    <div class="l-sidebar__content">
      <nav class="c-menu js-menu">
        <ul class="u-list">
          <!-- Single Menu -->
          <a href="index.html">
	          <li class="c-menu__item c-menu__single" data-toggle="tooltip" title="Dashboard">
              <div class="c-menu-item__wrapper desktop-menu-list">
                <i class="fa fa-dashboard"></i>
  	            <div class="c-menu-item__title"><span>Dashboard</span></div>
              </div>
            </li>
          </a>
          <!-- Dropdown Menu -->
		  <li class="c-menu__item c-menu__submenu" data-toggle="tooltip" title="Report">
		 	<div class="c-menu-item__wrapper desktop-menu-list" data-submenu="report-list-menu" submenu="close">
			  <i class="ti-stats-up"></i>
			  <div class="c-menu-item__title"><span>Reports</span></div>
			  <i class="ti-angle-down angle-submenu"></i>
			</div>
			<div class="submenu" data-content-submenu="report-list-menu">
			  <div class="submenu__wrapper">
				<ul>
					<li>
						<a class="is-submenu" href="reports_sales.html" title="Sales">Sales</a>
					</li>
					<li>
						<a class="is-submenu" href="reports_transactions.html" title="Transactions">Transactions</a>
					</li>
				</ul>
			  </div>
			</div>
		  </li>
        </ul>
      </nav>
    </div>
  </div>
