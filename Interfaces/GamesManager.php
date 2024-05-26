<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
    $redirect("Login.php");

elseif ($user["type"] == "student")
    $redirect("Portal.php");

$res = $conn->query("
    SELECT COUNT(id) AS count
    FROM Arguments
    GROUP BY id
");

if ($res->num_rows <= 0 || $res->fetch_assoc()["count"] <= 0)
    $redirect("Arguments.php");
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
                include_once(__DIR__. "/../Sources/SecureSQL.php");
                include_once(__DIR__. "/../Sources/Errors.php");

                $game = "";
                if (isset($_POST["game"])) {
                    $game = (int) $secureSQL($_POST["game"]);
                    
                    if (isset($_POST["updateGame"])) {
                        $succ = $conn->query("
                            UPDATE Games 
                            SET 
                                title = '". $secureSQL($_POST["title"]) ."', 
                                description = '". $secureSQL($_POST["description"]) ."', 
                                argument = ". (int) $secureSQL($_POST["argument"]) .", 
                                coins = ". $secureSQL($_POST["reward"]) ." 
                            WHERE id = ". $game
                        );
                        
                        if (!$succ)
                            $error("Unable to make changes to the game!");

                        $classes = [];
                        if (isset($_POST["classes"]))
                            foreach ($_POST["classes"] as $class)
                                array_push($classes, $secureSQL($class));
    
                        $succ = $conn->query("
                            DELETE FROM LinksGames 
                            WHERE game = ". $game .(count($classes) > 0 ? (" AND virtualClass NOT IN (". implode(',', $classes) .")") : "")
                        );

                        if (!$succ)
                            $error("Unable to remove classes!");

                        if (count($classes) > 0) {
                            $addQuery = "INSERT IGNORE INTO LinksGames(game, virtualClass) VALUES ";
                            foreach ($classes as $class)
                                $addQuery .= " (". $game .", ". (int) $class ."),";

                            $addQuery = substr_replace($addQuery, '', -1);
                            $succ = $conn->query($addQuery);

                            if (!$succ)
                                $error("Unable to add classes!");
                        }

                    } elseif (isset($_POST["removeGame"])) {
                        $succ = $conn->query("
                            DELETE FROM Games
                            WHERE id = ". $game
                        );
                        
                        if (!$succ)
                            $error("Unable to delete the game!");

                        $game = $succ ? "" : $game;
                    }
                } 

                if (isset($_POST["addGame"])) {
                    $succ = $conn->query("
                        INSERT INTO Games(title, description, argument, coins) 
                        VALUES ('New game', 'So cool!', (
                            SELECT id 
                            FROM Arguments 
                            ORDER BY tag ASC 
                            LIMIT 1
                        ), 0)
                    ");

                    if ($succ)
                        $game = $conn->insert_id;
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
            </select>

            <form method="post">
                <input type="submit" name="addGame" value="+">
            </form>

            <div class="scrollable">
                <h2>Games</h2>

                <?php
                    $info = [
                        "description" => "",
                        "title" => "",
                        "coins" => 0,
                        "argId" => 0,
                        "uses" => 0,
                        "id" => 0
                    ];

                    if ($game != "") {
                        $result = $conn->query("
                            SELECT g.*, a.id AS argId, COUNT(v.id) AS uses 
                            FROM Games g 
                            JOIN Arguments a ON g.argument = a.id 
                            LEFT JOIN LinksGames l ON g.id = l.game 
                            LEFT JOIN VirtualClasses v ON v.id = l.virtualClass 
                            WHERE g.id = ". $game ."
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

                <form method="post">
                    <td class="box"><input type="hidden" name="game" value="<?php echo $info["id"]; ?>" required>

                    <table>
                        <tr><td class="field">Argument:</td> <td class="box"><select name="argument" required>
                            <?php $argumentselector($info["argId"]); ?>
                        </select></td></tr>

                        <tr><td class="field">Title:</td> <td class="box"><input type="textbox" name="title" value="<?php echo $info["title"]; ?>" required></td></tr>
                        <tr><td class="field">Description:</td> <td class="box"><input type="textfield" name="description" value="<?php echo $info["description"]; ?>" required></td></tr>
                        <tr><td class="field">Reward:</td> <td class="box"><input type="number" name="reward" value="<?php echo $info["coins"]; ?>" required></td></tr>
                    </table>
                    
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
                                    WHERE g.id = ". $game ." 
                                    GROUP BY v.id 
                                    ORDER BY v.tag ASC
                                ");

                                $blacklist = [];
                                if ($classes->num_rows > 0)
                                    while($row = $classes->fetch_assoc()) {
                                        echo "<tr><td class='field'>". $row["tag"] ."</td><td class='box'><input name='classes[]' type='checkbox' value='". $row["id"] ."' checked></td></tr>";
                                        array_push($blacklist, $row["id"]);
                                    }

                                $classes = $conn->query("
                                    SELECT id, tag 
                                    FROM VirtualClasses ".
                                    (count($blacklist) > 0 ? "WHERE id NOT IN (". implode(',', $blacklist) .") " : "").
                                    "ORDER BY tag ASC
                                ");

                                if ($classes->num_rows > 0)
                                    while($row = $classes->fetch_assoc())
                                        echo "<tr><td class='field'>". $row["tag"] ."</td><td class='box'><input name='classes[]' type='checkbox' value='". $row["id"] ."'></td></tr>";
                            }
                        ?>
                        </table>
                    </div><br>

                    <input type="submit" name="updateGame" value="Save">
                </form>

                <form method="post">
                    <td class="box"><input type="hidden" name="game" value="<?php echo $info["id"]; ?>" required>
                    <input type="submit" name="removeGame" value="Remove">
                </form>
            </div><br>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Back">
            </form>
        </div>
    </body>
</html>