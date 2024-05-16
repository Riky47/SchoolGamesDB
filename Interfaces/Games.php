<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
    $redirect("Login.php");
?>

<html>
    <head>
        <script>
            var loaded = false
            function updateGames() {
                const value = document.getElementById("argumentSelector").value
                const input = document.getElementById("argumentInput")
                const form = document.getElementById("updateForm")

                input.value = value
                if (loaded)
                    form.submit()

                loaded = true
            }

            window.onload = updateGames
        </script>

        <form id="updateForm" method="post">
            <input id="argumentInput" type="hidden" name="argument">
        </form>

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
                $arg = "";
                if (isset($_POST["argument"]))
                    $arg = $_POST["argument"];
            ?>

            <select id="argumentSelector" onchange="updateGames()">
                <?php
                    include_once(__DIR__. "/../Sources/Selectors.php");
                    $argumentselector($arg);
                    
                ?>
            </select>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Back">
            </form>
        </div>
    </body>
</html>