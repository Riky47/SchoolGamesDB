<?php
if (!isset($conn))
    include_once(__DIR__. "/Connect.php");

$secureSQL = function($variable) use ($conn) {
    return filter_var($conn->real_escape_string($variable), FILTER_SANITIZE_STRING);
};
?>