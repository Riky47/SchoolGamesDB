<!DOCTYPE html>

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
            <?php
                include_once(__DIR__. "/../Sources/User.php");
                $user = $getuser();
            ?>

            <h2>Welcome <?php echo ($user ? $user["username"] : "user"); ?>!</h2>
            <p>The web database manager for school themed games!</p>

            <h3>What we provide?</h3>
            <p>Our system allows students to play subjects-themed games uploaded by teachers and earn coins!</p>

            <h3>The project</h3>
            <p>Our system, mainly built in Php and JavaScript, makes use of MySQL database to securily store data by protecting it from SQL Injection, passowrds are hashed with Argon2 before being stored and your connection is secured by a token session.</p>

            <table><tr>
                <td class="field"><form action="<?php echo ($user ? "Portal.php" : "Login.php"); ?>" method="get">
                    <input type="submit" class="submit" value="<?php echo ($user ? "Portal" : "Sign-In"); ?>">
                </form></td>
                
                <td class="field"><form action="<?php echo ($user ? "Login.php" : "Register.php"); ?>" method="post">
                    <input type="submit" class="submit" name="logout" value="<?php echo ($user ? "Logout" : "Register"); ?>">
                </form></td>
            </tr></table>
        </div>
    </body>
</html>