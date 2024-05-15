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
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Login</h3>

            <?php
                include_once(__DIR__. "/../Sources/Errors.php");

                if (isset($_POST["logout"])) {
                    session_destroy();
                    $error("You have been logged out!");

                } elseif (isset($_POST["login"])) {
                    include_once(__DIR__. "/../Sources/SecureSQL.php");

                    $email = $secureSQL($_POST["email"]);
                    $isStudent = true;
                    $missing = false;

                    $user = $conn->query("SELECT id, password FROM Students WHERE email = '". $email ."';");
                    if ($user->num_rows <= 0) {
                        $user = $conn->query("SELECT id, password FROM Teachers WHERE email = '". $email ."';");
                        $isStudent = false;
                        
                        if ($user->num_rows <= 0) {
                            $missing = true;
                            $error("Account not found!");
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
                            $error("Invalid credentials!");
                    }
                }
            ?>

            <form method="post">
                <table>
                <tr><td class="field">EMail:</td> <td class="box"><input type="email" name="email" placeholder="mario.rossi@email.com" required></td></tr>
                <tr><td class="field">Password:</td> <td class="box"><input type="password" name="password" placeholder="M****R****1!" required></td></tr>
                <table>

                <br><input type="submit" class="submit" name="login" value="Login">
            </form>

            <form action="Register.php" method="get">
                <input type="submit" class="submit" value="Register">
            </form>
        </div>
    </body>
</html>