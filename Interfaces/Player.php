<?php
include_once(__DIR__. "/../Sources/SecureSQL.php");
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
    $redirect("Login.php");

if($user["type"] != "student")
    $redirect("Portal.php");

if(!isset($_POST["game"]))
    $redirect("Games.php");

$game = $secureSQL($_POST["game"]);
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
            <?php
                include_once(__DIR__. "/../Sources/Errors.php");
                $awarded = 0;
                $coins = 0;

                $info = $conn->query("
                    SELECT * 
                    FROM Games 
                    WHERE id = ". $game
                );

                if ($info->num_rows > 0) {
                    $assoc = $info->fetch_assoc();
                    $coins = $assoc["coins"];

                    echo "<h3>You are playing ". $assoc["title"] ."!</h3>";
                    $rewarded = $conn->query("
                        SELECT coins 
                        FROM Rewards 
                        WHERE student = ". $user["id"] ." AND game = ". $game
                    );
    
                    $found = false;
                    if ($rewarded->num_rows > 0) {
                        $awarded = $rewarded->fetch_assoc()["coins"];
                        $found = true;
                    }

                    if (isset($_POST["collect"])) {
                        $value = min(max(intval($secureSQL($_POST["reward"])), 0), $coins);
                        $res = $conn->query(($found ? "
                            UPDATE Rewards 
                            SET coins = $value 
                            WHERE student = ". $user["id"] ." AND game = $game
                            " : "
                            INSERT IGNORE INTO Rewards(coins, student, game) 
                            VALUES ($value, ". $user["id"] .", $game)
                        "));

                        if ($res)
                            $awarded = $value;
                        else
                            $error("Unable to claim your reward!");
                    }
                }
                else
                    $error("The selected game has not been found!");
            ?>

            <form method="post">
                <input type="hidden" name="game" value="<?php echo $game; ?>">

                <p>Coins in game:</p>
                <select name="reward" required>
                    <?php
                        for ($i = 0; $i <= $coins; $i++)
                            echo "<option value=$i". ($i == $awarded ? " selected" : "") .">$i</option>";
                    ?>
                </select><br>

                <input type="submit" name="collect" value="Collect coins">
                <p>This will set your coins for the current game</p>
            </form>

            <form action="Games.php" method="get">
                <input type="submit" class="submit" value="Quit game">
            </form>
        </div>
    </body>
</html>