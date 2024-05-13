<!DOCTYPE html>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <h3>Login</h3>

        <?php
            if (isset($_POST["login"])) {
                include_once(__DIR__. "/../Sources/SecureSQL.php");

                $error = "<h3 class='error'>Invalid credentials!</h3>";
                $email = $secureSQL($_POST["email"]);
                $missing = false;

                $user = $conn->query("SELECT * FROM Students WHERE email = '". $email ."';");
                if ($user->num_rows <= 0) {
                    $user = $conn->query("SELECT * FROM Teachers WHERE email = '". $email ."';");
                    if ($user->num_rows <= 0) {
                        echo $error;
                        $missing = true;
                    }
                }

                if (!$missing) {
                    $user = $user->fetch_assoc();
                    if (password_verify($_POST["password"], $user["password"]))
                        echo "nice";
                    else
                        echo $error;
                }
            }
        ?>

        <div id="leaderDiv">
            <form method="post" action="">
                EMail: <input type="email" name="email" placeholder="mario.rossi@email.com" required><br>
                Password: <input type="password" name="password" placeholder="M****R****1!" required><br>

                <br><input type="submit" class="submit" name="login" value="Login">
            </form>
        </div>
    </body>
</html>