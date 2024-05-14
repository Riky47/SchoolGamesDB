<!DOCTYPE html>
<?php session_start(); ?>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <div id="leaderDiv">
            <?php 
                include_once(__DIR__. "/../Sources/Connect.php");
                $default = "
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

                if (isset($_SESSION["isStudent"])) {
                    $isStudent = $_SESSION["isStudent"];
                    $user = $conn->query("SELECT name FROM ". ($isStudent ? "Students" : "Teachers") ." WHERE id = ". $_SESSION["userId"]);
                    
                    if ($user->num_rows > 0) {
                        echo "<h3>Welcome back ". $user->fetch_assoc()["name"] ."!</h3>";
                        echo "
                        <table><tr>
                        <td class='field'><form action='". ($isStudent ? "Student.php" : "Teacher.php") ."' method='get'>
                            <input type='submit' class='submit' value='Personal Area'/>
                        </form></td>

                        <td class='field'><form action='Logout.php' method='get'>
                            <input type='submit' class='submit' value='Log-Out'/>
                        </form></td>
                        </tr></table>
                        ";
                    }
                    else
                        echo $default;
                }
                else
                    echo $default;
            ?>
        </div>
    </body>
</html>