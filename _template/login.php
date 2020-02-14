<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="robots" content="noindex, nofollow" />
<title>Login</title>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_URI ?>favicon.ico" />
<!--[if IE]>
<script>(function(){var a=("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(",");for(var b=0;b<a.length;b++){document.createElement(a[b])}try{document.execCommand("BackgroundImageCache",false,true)}catch(c){}})();</script>
<![endif]-->
<style>
body{background:#bce2e8;background:-webkit-linear-gradient(right,#bce2e8,#b1e1e8);background:-moz-linear-gradient(right,#bce2e8,#b1e1e8);background:-o-linear-gradient(right,#bce2e8,#b1e1e8);background:linear-gradient(to left,#bce2e8,#b1e1e8);font-family:arial,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
.login-page{width:600px;padding:2% 0;margin:0 auto}
.form{position:relative;z-index:1;background:#fff;margin:0 auto;padding:30px 45px;box-shadow:0 0 20px 0 rgba(0,0,0,0.2),0 5px 5px 0 rgba(0,0,0,0.24)}
.form input{outline:0;background:#f2f2f2;width:100%;border:1px solid #cbd5de;margin:0 0 15px;padding:15px;box-sizing:border-box;font-size:14px}
.form input:focus{border-color:#899fb1}
.form button{text-transform:uppercase;outline:0;background:#4c81af;width:100%;border:0;padding:15px;color:#fff;font-size:14px;-webkit-transition:all .3 ease;transition:all .3 ease;cursor:pointer}
.form h1{margin:0;text-align:center}
.form button:hover,.form button:active,.form button:focus{background:#3270a5}
.form .warning{margin:5px 0;background:#fff6bf;border:1px solid #ffd324;padding:6px;color:#817134;cursor:default;text-align:left;font-size:14px}
.form .failed{margin:5px 0;background:#fbe3e4;border:1px solid #fbc2c4;padding:6px;color:#d12f19;cursor:default;text-align:left;font-size:14px}
@media all and (max-width:640px){
	.login-page{
		width:auto;
		margin:0 auto;
	}
	.login-page img{width:150px}
	.login-page h1{font-size:26px}
}
</style>
</head>
<body>
<div class="login-page">
<div class="form">
<h1>Login</h1>
<?php
if($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';
elseif($msg['failed'])
	echo '<p class="failed">',$msg['failed'],'</p>';
else
	echo '<p class="warning">Silahkan login untuk mengakses halaman ini!</p>';
?>
<form action="" method="post" class="login-form">
<input type="<?php echo $conf['login_name']['type'] ?>" name="<?php echo $conf['login_name']['name'] ?>" value="<?php echo $_POST[$conf['login_name']['name']] ?>" placeholder="<?php echo $conf['login_name']['placeholder'] ?>"<?php echo !$_POST[$conf['login_name']['name']] ? ' autofocus' : '' ?> required />
<input type="password" name="password" placeholder="Password"<?php echo $_POST[$conf['login_name']['name']] ? ' autofocus' : '' ?> required />
<button type="submit">Login</button>
<input type="hidden" name="next" value="<?php echo $_GET['next'] ? $_GET['next'] : '' ?>" />
</form>
</div>
</div>
</body>
</html>