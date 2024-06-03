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
                document.getElementById("argumentInput").value = document.getElementById("argumentSelector").value
                if (loaded)
                    document.getElementById("updateForm").submit()

                loaded = true
            }

            function previewGame(id, title, desc, coins) {
                document.getElementById("gameReward").innerHTML = "Reward: " + coins
                document.getElementById("gameDescription").innerHTML = desc
                document.getElementById("gameTitle").innerHTML = title
                document.getElementById("playInput").value = id
            }

            function playGame() {
                const value = document.getElementById("playInput").value
                if (value && value != "" && value > 0)
                    document.getElementById("playForm").submit()
            }

            function load() {
                updateGames()
                const btn = document.getElementById("firstGame")
                if (btn)
                    btn.click()
            }

            window.onload = load
        </script>

        <form id="updateForm" method="post">
            <input id="argumentInput" type="hidden" name="argument">
        </form>

        <form id="playForm" action="Player.php" method="post">
            <input id="playInput" type="hidden" name="game">
        </form>

        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/GamesStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h2>Games</h2>
            <?php
                include_once(__DIR__. "/../Sources/Selectors.php");
                include_once(__DIR__. "/../Sources/Errors.php");

                $arg = "";
                if (isset($_POST["argument"]))
                    $arg = $_POST["argument"];

                else {
                    $result = $conn->query("
                        SELECT id 
                        FROM Arguments 
                        ORDER BY tag ASC 
                        LIMIT 1
                    ");

                    if ($result->num_rows > 0)
                        $arg = $result->fetch_assoc()["id"];
                }
            ?>

            <select id="argumentSelector" onchange="updateGames()">
                <?php $argumentselector($arg); ?>
            </select><br><br>

            <h3>Games list</h3>
            <div class="scrollable">
                <?php
                    if ($arg != "") {
                        include_once(__DIR__. "/../Sources/SecureSQL.php");
                        $games = $conn->query("
                            SELECT g.id, g.title, g.description, g.coins 
                            FROM Games g
                            JOIN LinksGames lg ON g.id = lg.game 
                            JOIN LinksUsers lu ON lu.student = ". $user["id"] ." 
                            WHERE argument = ". $secureSQL($arg) ." AND lg.virtualClass = lu.virtualClass 
                            ORDER BY title ASC
                        ");

                        if ($games->num_rows > 0) {
                            $first = true;
                            while ($row = $games->fetch_assoc()) {
                                $params = "previewGame('". $row["id"] ."', '". $row["title"] ."', '". $row["description"]. "', '". $row["coins"] ."')";
                                echo "<button ". ($first ? "id='firstGame'" : "") ." onclick=\"" .$params. "\">". $row["title"] ."</button><br>";
                                $first = false;
                            }

                        } else
                            $error("No games found for this argument for you, if you cant find any make sure to be part of a virtual class!");

                    } else
                        $error("No argument found!");
                ?>
            </div><br>

            <h3>Info</h3>
            <div>
                <h4 id="gameTitle">Title</h4    >
                <p id="gameDescription">Description</p>
                <p id="gameReward">Reward: 0</p>
                
                <button onclick="playGame()">Play</button>
            </div><br>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Back">
            </form>
        </div>
    </body>
</html>