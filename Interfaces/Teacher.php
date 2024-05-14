<?php
session_start();
include_once(__DIR__. "/../Sources/Redirect.php");

if (isset($_SESSION["isStudent"])) {
    if ($_SESSION["isStudent"])
        $redirect("Student.php");
} else
    $redirect("Login.php");
?>