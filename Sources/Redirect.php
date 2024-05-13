<?php
if (!isset($configs))
    include_once(__DIR__. "/../Configs.php");

$redirect = function($page) use ($configs) {
    header("Location: https://". $configs["host"] ."/". $page);
die();
};
?>