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

                if ($user) {
                    echo "<h3>Welcome back ". $user["name"] ."!</h3>";
                    echo "
                    <table><tr>
                    <td class='field'><form action='". ($user["type"] == "student" ? "Student.php" : "Teacher.php") ."' method='get'>
                        <input type='submit' class='submit' value='Personal Area'/>
                    </form></td>

                    <td class='field'><form action='Login.php' method='post'>
                        <input type='submit' class='submit' name='logout' value='Log-Out'/>
                    </form></td>
                    </tr></table>
                    ";

                } else
                    echo "
                    <h3>Welcome user!</h3>
                    <p>The web database manager for school themed games!</p>
    
                    <table><tr>
                    <td class='field'><form action='Login.php' method='get'>
                        <input type='submit' class='submit' value='Sign-In'/>
                    </form></td>
            
                    <td class='field'><form action='Register.php' method='get'>
                        <input type='submit' class='submit' value='Register'/>
                    </form></td>
                    </tr></table>
                    ";
            ?>
        </div>
    </body>
</html>