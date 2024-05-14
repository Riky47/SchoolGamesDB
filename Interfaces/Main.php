<!DOCTYPE html>
<?php session_start(); ?>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <?php 
            include_once(__DIR__. "/../Sources/Connect.php");
            $default = "
            <form action='Login.php' method='get'>
                <input type='submit' class='submit' value='Sign-In'/>
            </form>
    
            <form action='Register.php' method='get'>
                <input type='submit' class='submit' value='Register'/>
            </form>
            ";

            if (isset($_SESSION["isStudent"])) {
                $isStudent = $_SESSION["isStudent"];
                $user = $conn->query("SELECT name FROM ". ($isStudent ? "Students" : "Teachers") ." WHERE id = ". $_SESSION["userId"]);
                
                if ($user->num_rows > 0) {
                    echo "Welcome back ". $user->fetch_assoc()["name"] ."!";
                    echo "
                    <form action='". ($isStudent ? "Student.php" : "Teacher.php") ."' method='get'>
                        <input type='submit' class='submit' value='Personal Area'/>
                    </form>

                    <form action='Logout.php' method='get'>
                        <input type='submit' class='submit' value='Log-Out'/>
                    </form>
                    ";
                }
                else
                    echo $default;
            }
            else
                echo $default;
        ?>
    </body>
</html>