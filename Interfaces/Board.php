<?php
    include_once(__DIR__. "/../Sources/User.php");
    $user = $getuser();
?>

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
            <div>
                <h3>Your score</h3>
                <?php
                    include_once(__DIR__. "/../Sources/SecureSQL.php");
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
                ?>

                <div class="scrollable">
                    <?php
                        if ($user) {
                            $res = $conn->query("
                                SELECT game, coins 
                                FROM Rewards 
                                WHERE student = ". $user["id"]
                            );

                            if ($res->num_rows > 0)
                                while ($row = $res->fetch_assoc())
                                    echo "<p>". $row["game"] ." - ". $row["coins"] ."</p>";
                        }
                    ?>
                </div>
            <div>

            <div>
                <h3>Leaderboard</h3>
                <?php
                    $query = "
                        SELECT SUM(r.coins) 
                        FROM Rewards r";

                    if (isset($_POST["vclass"]))
                        $query .= " JOIN LinksUsers l ON r.student = l.user 
                        JOIN VirtualClasses v ON l.virtualClass = v.id";

                    if (isset($_POST["game"]) && $_POST["game"] != "0")
                        $query .= " WHERE r.game = ". $secureSQL($_POST["game"]);

                    $res = $conn->query($query);
                ?>
            <div>
        </div>
    </body>
</html>