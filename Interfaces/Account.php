<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
    $redirect("Login.php");
?>

<!DOCTYPE html>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/AccountStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Account</h3>
            <?php
                include_once(__DIR__. "/../Sources/SecureSQL.php");
                include_once(__DIR__. "/../Sources/Errors.php");

                $passcheck = function() use($user, $error) {
                    $auth = password_verify($_POST["oldPassword"], $user["password"]);
                    if (!$auth)
                        $error("Invalid current password!");

                    return $auth;
                };

                $table = ($user["type"] == "student" ? "Students" : "Teachers");
                if (isset($_POST["save"]) && $passcheck()) {
                    $res = $conn->query("
                        UPDATE ". $table ." 
                        SET 
                            name = '". $secureSQL($_POST["name"]) ."', 
                            surname = '". $secureSQL($_POST["surname"]) ."', 
                            username = '". $secureSQL($_POST["username"]) ."', 
                            email = '". $secureSQL($_POST["email"]) ."', 
                            password = '". ((isset($_POST["password"]) && trim($_POST["password"]) != "") ? password_hash($_POST["password"], PASSWORD_ARGON2I) : $user["password"]) ."' ".
                            ($user["type"] == "student" ? (", class = ". $secureSQL($_POST["class"] ." ")) : "") ."
                        WHERE id = ". $user["id"]
                    );

                    if ($res)
                        $user = $getuser();
                    else
                        $error("Unable to make changes to the account!");

                } elseif (isset($_POST["delete"]) && $passcheck()) {
                    $res = $conn->query("
                        DELETE FROM ". $table ." 
                        WHERE id = ". $user["id"]
                    );

                    if ($res)
                        $redirect("Login.php");
                    else
                        $error("Unable to delete the account!");

                }
            ?>

            <form method="post" id="mainForm">
                <table id="mainTable">
                    <tr><td class="field">Type:</td> <td class="box"><input type="text" value="<?php echo $user["type"]; ?>" readonly></td></tr>

                    <tr><td class="field">Name:</td> <td class="box"><input type="text" name="name"  value="<?php echo $user["name"]; ?>" required></td></tr>
                    <tr><td class="field">Surname:</td> <td class="box"><input type="text" name="surname"  value="<?php echo $user["surname"]; ?>" required></td></tr>
                    <tr><td class="field">Nickname:</td> <td class="box"><input type="text" name="username"  value="<?php echo $user["username"]; ?>" required></td></tr>
                    <tr><td class="field">EMail:</td> <td class="box"><input type="email" name="email"  value="<?php echo $user["email"]; ?>" required></td></tr>
                    <tr><td class="field">Password:</td> <td class="box"><input type="password" name="password" placeholder="M****R****1!"></td></tr>
                    <tr><td class="field">Current password:</td> <td class="box"><input type="password" name="oldPassword" placeholder="M****R****1!" required></td></tr>

                    <?php
                        if ($user["type"] == "student") {
                            include_once(__DIR__. "/../Sources/Selectors.php");
                            echo "<tr><td class='field'>Class:</td> <td class='box'><select class='selector' name='class' required>";
                            $classselector($user["class"]);
                            echo"</select></td></tr>";
                        }
                    ?>
                </table>

                <br><input type="submit" class="submit" name="save" value="Save">
                <br><input type="submit" class="submit" name="delete" value="Delete">
            </form>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Discard"/>
            </form>
        </div>
    </body>
</html>