<?php
session_start();
include_once(__DIR__. "/../Sources/Redirect.php");

if (isset($_SESSION["isStudent"])) {
    if ($_SESSION["isStudent"])
        $redirect("Student.php");
} else
    $redirect("Login.php");
?>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Login</h3>

            
        </div>
    </body>
</html>