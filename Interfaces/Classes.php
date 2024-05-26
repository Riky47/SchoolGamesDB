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
            <select id="classSelector" onchange="updateClass()">
                <?php $classselector($game); ?>
            </select>

            
        </div>
    </body>
</html>