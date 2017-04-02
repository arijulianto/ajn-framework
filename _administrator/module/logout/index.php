<?php
session_destroy();

header('location:'.ADMIN_URI.'login.php');