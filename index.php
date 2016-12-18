<?php

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

include_once("./config.php");

include_once("./class/Template.class.php");

$template = new Template();

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "<!-- Execution time : ".$time." -->";

?>