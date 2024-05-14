<!DOCTYPE html>

<?php
session_start();
session_destroy();
?>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <h2>You have been logged out!</h2>

        <form action="Login.php" method="get">
            <input type="submit" value="Sign-In"/>
        </form>

        <form action="Register.php" method="get">
            <input type="submit" value="Register"/>
        </form>
    </body>
</html>