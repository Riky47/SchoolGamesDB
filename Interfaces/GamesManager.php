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
                if (isset($_POST["game"])) {
                    $game = $_POST["game"];
                    
                    if (isset($_POST["removeGame"])) {
                        $succ = $conn->query("
                            DELETE FROM Games
                            WHERE id = ". $game ."
                        ");
                        
                        if (!$succ)
                            $error("Unable to delete the game!");

                        $game = $succ ? "" : $game;
                    }
                } 
                
                if ($game == "") {
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
                    $info = [
                        "description" => "",
                        "title" => "",
                        "coins" => "",
                        "argId" => "",
                        "uses" => 0,
                        "id" => ""
                    ];

                    if ($game != "") {
                        include_once(__DIR__. "/../Sources/SecureSQL.php");
                        $result = $conn->query("
                            SELECT g.*, a.id AS argId, COUNT(v.id) AS uses 
                            FROM Games g 
                            JOIN Arguments a ON g.argument = a.id 
                            JOIN LinksGames l ON g.id = l.game 
                            JOIN VirtualClasses v ON v.id = l.virtualClass 
                            WHERE g.id = ". $secureSQL($game) ."
                            GROUP BY g.id, a.tag 
                            ORDER BY g.title ASC 
                            LIMIT 1
                        ");
                        
                        if ($result->num_rows > 0)
                            $info = $result->fetch_assoc();

                        else
                            $error("No infos found!");
                    }
                    else
                        $error("No game found!");
                ?>

                <h3>Being used in <?php echo $info["uses"]; ?> class<?php 
                    if ($info["uses"] > 1 || $info["uses"] == 0)
                        echo "es";
                ?></h3>

                <select>
                    <?php $argumentselector($info["argId"]); ?>
                </select>

                <form method="post">
                    <input type="hidden" name="game" value="<?php echo $info["id"]; ?>" required>
                    <input type="textbox" name="title" value="<?php echo $info["title"]; ?>" required>
                    <input type="textfield" name="description" value="<?php echo $info["description"]; ?>" required>
                    <input type="textbox" name="reward" value="<?php echo $info["coins"]; ?>" required>
                    
                    <h2>Virtual classes</h2>
                    <div class="scrollable">
                        <table>
                        <?php
                            if ($game != "") {
                                $classes = $conn->query("
                                    SELECT v.id, v.tag 
                                    FROM VirtualClasses v 
                                    JOIN LinksGames l ON v.id = l.virtualClass 
                                    JOIN Games g ON l.game = g.id 
                                    WHERE g.id = ". $secureSQL($game) ." 
                                    GROUP BY v.id 
                                    ORDER BY v.tag ASC
                                ");

                                $blacklist = [];
                                if ($classes->num_rows > 0)
                                    while($row = $classes->fetch_assoc()) {
                                        echo "<tr><td class='field'>". $row["tag"] ."</td><td class='box'><input name='classes[]' type='checkbox' checked></td></tr>";
                                        array_push($blacklist, $row["id"]);
                                    }

                                $blacklist = empty($blacklist) ? "NULL" : implode(',', $blacklist);
                                $classes = $conn->query("
                                    SELECT id, tag 
                                    FROM VirtualClasses 
                                    WHERE id NOT IN ($blacklist) 
                                    ORDER BY tag ASC
                                ");

                                if ($classes->num_rows > 0)
                                    while($row = $classes->fetch_assoc())
                                        echo "<tr><td class='field'>". $row["tag"] ."</td><td class='box'><input name='classes[]' type='checkbox'></td></tr>";
                            }
                        ?>
                        </table>
                    </div><br>

                    <input type="submit" name="updateGame" value="Save">
                    <input type="submit" name="removeGame" value="Remove">
                </form>
            </div><br>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Back">
            </form>
        </div>
    </body>
</html>