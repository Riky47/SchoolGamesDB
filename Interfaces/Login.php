<!DOCTYPE html>
<?php session_start(); ?>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <div id="leaderDiv">
            <h3>Login</h3>

            <?php
                if (isset($_POST["login"])) {
                    include_once(__DIR__. "/../Sources/SecureSQL.php");

                    $error = "<h3 class='error'>Invalid credentials!</h3>";
                    $email = $secureSQL($_POST["email"]);
                    $isStudent = true;
                    $missing = false;

                    $user = $conn->query("SELECT id, password FROM Students WHERE email = '". $email ."';");
                    if ($user->num_rows <= 0) {
                        $user = $conn->query("SELECT id, password FROM Teachers WHERE email = '". $email ."';");
                        $isStudent = false;
                        
                        if ($user->num_rows <= 0) {
                            $missing = true;
                            echo $error;
                        }
                    }

                    if (!$missing) {
                        $user = $user->fetch_assoc();
                        include_once(__DIR__. "/../Sources/Redirect.php");

                        if (password_verify($_POST["password"], $user["password"])) {
                            $_SESSION["isStudent"] = $isStudent;
                            $_SESSION["userId"] = $user["id"];

                            $redirect($isStudent ? "Student.php" : "Teacher.php");
                        } else
                            echo $error;
                    }
                }
            ?>

            <form method="post" action="">
                EMail: <input type="email" name="email" placeholder="mario.rossi@email.com" required><br>
                Password: <input type="password" name="password" placeholder="M****R****1!" required><br>

                <br><input type="submit" class="submit" name="login" value="Login">
            </form>

            <form action="Register.php" method="get">
                <input type="submit" class="submit" value="Register"/>
            </form>
        </div>
    </body>
</html>