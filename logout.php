<?php 


require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");


$_SESSION['AdminName'] = null ;

session_destroy() ;

Redirect_to("login.php");











?>