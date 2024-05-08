<!DOCTYPE html>
<?php
    //connessione al server
     $conn = new mysqli ("localhost", "root", "", "Utenze");
    if ($conn->connect_errno) {
        echo"Impossibile connettersi al server: " . $conn->connect_error . "\n";
        exit;
    }
?>
<html>
    <head>
        <title>SchoolGamesDB</title>
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <h3>Login</h3>

        <form method="post" action="">
            <input type="text" name="surname" required>
            <submit type="submit" name="login">
        </form>
    </body>
</html>