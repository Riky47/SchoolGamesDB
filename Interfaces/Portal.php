<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
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
            <h3>Welcome back <?php echo $user["username"]; ?>!</h3>

            <form action="Games.php" method="get">
                <input type="submit" class="submit" value="Games">
            </form>

            <?php
                if ($user["type"] == "teacher")
                    echo "
                    <form action='GamesManager.php' method='get'>
                        <input type='submit' class='submit' value='Manage games'>
                    </form>

                    <form action='Arguments.php' method='get'>
                        <input type='submit' class='submit' value='Manage arguments'>
                    </form>

                    <form action='Classes.php' method='get'>
                        <input type='submit' class='submit' value='Manage classes'>
                    </form>
                    "
            ?>

            <form action="Account.php" method="get">
                <input type="submit" class="submit" value="Account">
            </form>

            <form action="Board.php" method="get">
                <input type="submit" class="submit" value="Check board">
            </form>

            <form action="Login.php" method="post">
                <input type="submit" class="submit" name="logout" value="Logout">
            </form>
        </div>
    </body>
</html>