<?php
    include_once(__DIR__. "/../Sources/User.php");
    $user = $getuser();
?>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/GamesStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <div>
                <h3>Your score</h3>
                <?php
                    include_once(__DIR__. "/../Sources/SecureSQL.php");
                    include_once(__DIR__. "/../Sources/Errors.php");

                    if ($user) {
                        if($user["type"] == "student") {
                            $score = 0;

                            if ($user) {
                                $res = $conn->query("
                                    SELECT SUM(coins) AS score
                                    FROM Rewards 
                                    WHERE student = ". $user["id"]. " 
                                    LIMIT 1
                                ");

                                if ($res->num_rows > 0)
                                    $score = $res->fetch_assoc()["score"];
                            }

                            echo "<h2>". (int)$score ."</h2>";

                        } else
                            $error("Only students are able to earn coins!");
                    } else
                        $error("Log in as student to see your score!");
                ?>

                <div class="scrollable">
                    <?php
                        if ($user) {
                            $res = $conn->query("
                                SELECT g.title, r.coins 
                                FROM Rewards r 
                                JOIN Games g ON r.game = g.id 
                                WHERE r.student = ". $user["id"] ." 
                                ORDER BY r.coins DESC
                            ");

                            if ($res->num_rows > 0)
                                while ($row = $res->fetch_assoc())
                                    echo "<p><strong>". $row["title"] ."</strong> - ". $row["coins"] ."</p>";

                            else
                                $error("You have not collected any coin yet!");
                        }
                    ?>
                </div>
            </div>

            <div>
                <h3>Leaderboard</h3>

                <form method="post">
                    <select class="selector" name="vclass" required>
                        <option value="0">All</option>
                        <?php
                            $class = "";
                            $isclass = isset($_POST["vclass"]);
                            if ($isclass) {
                                $class = $secureSQL($_POST["vclass"]);
                                $isclass = ($class != "0");
                            }

                            include_once(__DIR__. "/../Sources/Selectors.php");
                            $vclassselector($class);
                        ?>
                    </select>

                    <select class="selector" name="game" required>
                        <option value="0">All</option>
                        <?php
                            $game = "";
                            if (isset($_POST["game"]))
                                $game = $secureSQL($_POST["game"]);

                            $gamesselector($game);
                        ?>
                    </select>

                    <input class="submit" type="submit" value="set">
                </form>

                <div class="scrollable">
                    <?php
                        $qclass = ($isclass ? (" l.virtualClass = ". $class) : "");
                        $query = "
                            SELECT s.username, COALESCE(SUM(r.coins)) AS total
                            FROM Rewards r 
                            INNER JOIN Students s ON r.student = s.id 
                            JOIN LinksUsers l ON r.student = l.student
                        ";

                        if (isset($_POST["game"]) && $_POST["game"] != "0")
                            $query .= " WHERE r.game = ". $game .($isclass ? (" AND". $qclass) : "");

                        elseif ($isclass)
                            $query .= " WHERE". $qclass;

                        $query .= " 
                            GROUP BY r.student 
                            ORDER BY total DESC
                        ";

                        $res = $conn->query($query);
                        if ($res->num_rows > 0)
                            while ($row = $res->fetch_assoc())
                                echo "<p><strong>". $row["username"] ."</strong> - ". $row["total"] ."</p>";

                        else
                            $error("No data has been found for the current filters!");
                    ?>
                </div>

                <p>If you can't see your score in the leaderboard, you'll have to make sure you are assigned to at least 1 virtual class!</p>
                <form action="Portal.php" method="get">
                    <input type="submit" class="submit" value="Back">
                </form>
            </div>
        </div>
    </body>
</html>