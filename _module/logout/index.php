<?php
session_destroy();
if($_COOKIE['__ais_userlog']) setcookie('__ais_userlog', '', 0, SITE_URI);

setcookie('firstVisit', '', 0, SITE_URI);

header('location:'.SITE_URI);