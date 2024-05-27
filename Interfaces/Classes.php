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
    FROM Classes
    GROUP BY id
");

if ($res->num_rows <= 0 || $res->fetch_assoc()["count"] <= 0)
    $redirect("Class.php");

$res = $conn->query("
    SELECT COUNT(id) AS count
    FROM VirtualClasses
    GROUP BY id
");

if ($res->num_rows <= 0 || $res->fetch_assoc()["count"] <= 0)
    $redirect("VClass.php");
?>

<html>
    <head>
        <script>
            var loaded = false
            function updateClass() {
                document.getElementById("classInput").value = document.getElementById("classSelector").value
                if (loaded)
                    document.getElementById("classForm").submit()

                loaded = true
            }

            function load() {
                updateClass();
            }

            window.onload = load
        </script>

        <form id="classForm" method="post">
            <input id="classInput" type="hidden" name="class">
        </form>

        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Classes</h3>
            <?php
                include_once(__DIR__. "/../Sources/SecureSQL.php");
                include_once(__DIR__. "/../Sources/Selectors.php");
                $class = "";

                if (isset($_POST["class"]))
                    $class = $sercureSQL($_POST["class"]);
                
                else {
                    
                }
            ?>

            <select id="classSelector" onchange="updateClass()">
                <?php $classselector($class); ?>
            </select>

            <form action="Class.php" method="get">
                <input type="submit" value="+">
            </form>

            <div class="scrollable">
                <table>
                    <?php
                        if ($class != "") {
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
                                    echo "<tr><td class='field'>". $row["tag"] ."</td><td class='box'><input name='students[]' type='checkbox' value='". $row["id"] ."' checked></td></tr>";
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
                                    echo "<tr><td class='field'>". $row["tag"] ."</td><td class='box'><input name='students[]' type='checkbox' value='". $row["id"] ."'></td></tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>