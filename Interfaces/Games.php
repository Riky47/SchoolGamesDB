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
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Games</h3>
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

            <div class="scrollable">
                <h2>Games</h2>

                <?php
                    if ($arg != "") {
                        include_once(__DIR__. "/../Sources/SecureSQL.php");
                        $games = $conn->query("
                            SELECT * 
                            FROM Games 
                            WHERE argument = ". $secureSQL($arg) ." 
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
                            $error("No games found!");

                    } else
                        $error("No argument found!");
                ?>
            </div><br>

            <div class="scrollable">
                <h2>Info</h2>

                <h3 id="gameTitle">Title</h3>
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