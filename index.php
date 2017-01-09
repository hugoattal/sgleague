<?php

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 365);

session_start();

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

include_once("./config.php");

if ($_SERVER['HTTP_HOST'] != SERVER_ADDR)
{
	header('Location: http://'.SERVER_ADDR.SERVER_REP);
}

$csrf_check = false;

if (isset($_SERVER['HTTP_REFERER']))
{
	$csrf_check = (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == SERVER_ADDR);
}

include_once("./class/Template.class.php");

$template = new Template();

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "<!-- Execution time : ".$time." -->";

?>