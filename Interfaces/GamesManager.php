<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
    $redirect("Login.php");

elseif ($user["type"] == "student")
    $redirect("Portal.php");
?>

<html>
    <head>
        <script>
            var loaded = false
            function updateInfos() {
                document.getElementById("gameInput").value = document.getElementById("gameSelector").value
                if (loaded)
                    document.getElementById("updateForm").submit()

                loaded = true
            }

            window.onload = updateInfos
        </script>

        <form id="updateForm" method="post">
            <input id="gameInput" type="hidden" name="game">
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

                $game = "";
                if (isset($_POST["game"]))
                    $game = $_POST["game"];

                else {
                    $result = $conn->query("
                        SELECT id 
                        FROM Games 
                        ORDER BY title ASC 
                        LIMIT 1
                    ");

                    if ($result->num_rows > 0)
                        $game = $result->fetch_assoc()["id"];
                }
            ?>

            <select id="gameSelector" onchange="updateInfos()">
                <?php $gamesselector($game); ?>
            </select><br><br>

            <div class="scrollable">
                <h2>Games</h2>

                <?php
                    if ($game != "") {
                        include_once(__DIR__. "/../Sources/SecureSQL.php");
                        $info = $conn->query("
                            SELECT g.*, v.*, a.id AS argId, COUNT(v.id) AS uses 
                            FROM Games g 
                            JOIN Arguments a ON g.argument = a.id 
                            JOIN LinksGames l ON g.id = l.game 
                            JOIN VirtualClasses v ON v.id = l.virtualClass 
                            WHERE g.id = ". $secureSQL($game) ." 
                            GROUP BY g.id, v.id, a.tag 
                            ORDER BY g.title ASC 
                            LIMIT 1
                        ");
                        
                        if ($info->num_rows > 0)
                            $info = $info->fetch_assoc();

                        else {
                            $error("No infos found!");

                            $info = [
                                "id" => "",
                                "title" => "",
                                "description" => "",
                                "coins" => "",
                                "uses" => 0,
                                "title" => "",
                                "argId" => "",
                                "teacher" => "",
                                "subject" => ""
                            ];
                        }
                    }
                    else
                        $error("No game found!");
                ?>

                <h3>Being used in <?php echo $info["uses"]; ?> game<?php 
                    if ($info["uses"] > 1 || $info["uses"] == 0)
                        echo "s";
                ?></h3>

                <select>
                    <?php $argumentselector($info["argId"]); ?>
                </select>

                <form method="post">
                    <input type="hidden" name="gameId" value="<?php echo $info["id"]; ?>">
                    <input type="textbox" name="title" value="<?php echo $info["title"]; ?>">
                    <input type="textfield" name="description" value="<?php echo $info["description"]; ?>">
                    <input type="textbox" name="reward" value="<?php echo $info["coins"]; ?>">

                    <input type="submit" name="update" value="Save">
                </form>
            </div><br>

            <div class="scrollable">
                <h2>Virtual classes</h2>


            </div><br>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Back">
            </form>
        </div>
    </body>
</html>