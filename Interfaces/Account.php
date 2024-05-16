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
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Account</h3>
            
            <form method="post" id="mainForm">
                <table id="mainTable">
                    <tr><td class="field">Type:</td> <td class="box"><input type="text" name="type" value="<?php echo $user["type"]; ?>" readonly></td></tr>

                    <tr><td class="field">Name:</td> <td class="box"><input type="text" name="name"  value="<?php echo $user["name"]; ?>" required></td></tr>
                    <tr><td class="field">Surname:</td> <td class="box"><input type="text" name="surname"  value="<?php echo $user["surname"]; ?>" required></td></tr>
                    <tr><td class="field">Nickname:</td> <td class="box"><input type="text" name="username"  value="<?php echo $user["username"]; ?>" required></td></tr>
                    <tr><td class="field">EMail:</td> <td class="box"><input type="email" name="email"  value="<?php echo $user["email"]; ?>" required></td></tr>
                    <tr><td class="field">Password:</td> <td class="box"><input type="password" name="password" placeholder="M****R****1!" required></td></tr>

                    <?php
                        if ($user["type"] == "student") {
                            include_once(__DIR__. "/../Sources/Selectors.php");
                            echo "<tr><td class='field'>Class:</td> <td class='box'>";
                            $classselector();
                            echo"<td></tr>";
                        }
                    ?>
                </table>

                <br><input type="submit" class="submit" name="save" value="Save">
            </form>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Discard"/>
            </form>
        </div>
    </body>
</html>