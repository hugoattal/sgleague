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

$csrf_check = (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['SERVER_ADDR']);

include_once("./config.php");

include_once("./class/Template.class.php");

$template = new Template();

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "<!-- Execution time : ".$time." -->";

?>