<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
    $redirect("Login.php");
?>