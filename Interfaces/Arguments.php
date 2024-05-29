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
        <title>SchoolGamesDB</title>
        <script>
            var loaded = false
            function updateInfos() {
                document.getElementById("argumentInput").value = document.getElementById("argumentSelector").value
                if (loaded)
                    document.getElementById("updateForm").submit()

                loaded = true
            }

            window.onload = updateInfos
        </script>

        <form id="updateForm" method="post">
            <input id="argumentInput" type="hidden" name="argument">
        </form>

        <link rel="stylesheet" href="Styles/ArgumentStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Arguments</h3>
            <?php
                include_once(__DIR__. "/../Sources/SecureSQL.php");
                include_once(__DIR__. "/../Sources/Errors.php");

                $argument = "";
                $tag = "";

                $getFirstArgument = function() use($conn, $error, $argument, $tag) {
                    $info = $conn->query("
                        SELECT * 
                        FROM Arguments 
                        ORDER BY tag ASC 
                        LIMIT 1
                    ");

                    if ($info->num_rows > 0)
                        return $info->fetch_assoc();
                    
                    return false;
                };

                $checkPresence = function($tag) use($conn, $error) {
                    $res = $conn->query("
                        SELECT * 
                        FROM Arguments 
                        WHERE tag = '". $tag ."' 
                        LIMIT 1
                    ");

                    if ($res->num_rows > 0) {
                        $error("Title already in use!");
                        return true;
                    }

                    return false;
                };

                if (isset($_POST["argument"])) {
                    $argument = $secureSQL($_POST["argument"]);

                    if (isset($_POST["tag"]))
                        $tag = $secureSQL($_POST["tag"]);

                    else {
                        $info = $conn->query("
                            SELECT tag 
                            FROM Arguments 
                            WHERE id = ". $argument ." 
                            LIMIT 1
                        ");

                        if ($info->num_rows > 0)
                            $tag = $info->fetch_assoc()["tag"];
                        else
                            $error("Unable to find the selected argument!");
                    }

                    if (isset($_POST["save"])) {
                        if (!$checkPresence($tag)) {
                            $res = $conn->query("
                                UPDATE Arguments 
                                SET 
                                    tag = '". $tag ."' 
                                WHERE id = ". $argument
                            );

                            if (!$res)
                                $error("Unable to save changes to the argument!");
                        }

                    } elseif (isset($_POST["delete"])) {
                        $res = $conn->query("
                            DELETE FROM Arguments 
                            WHERE id = ". $argument
                        );

                        if ($res) {
                            $info = $getFirstArgument();
                            if ($info) {
                                $argument = $info["id"];
                                $tag = $info["tag"];
                            }

                        } else
                            $error("Unable to delete the selected argument!");

                    } elseif (isset($_POST["new"])) {
                        if (!$checkPresence($tag)) {
                            $res = $conn->query("
                                INSERT INTO Arguments(tag) 
                                VALUES ('". $tag ."')
                            ");

                            if ($res)
                                $argument = $conn->insert_id;
                            else
                                $error("Unable to create a new argument!");
                        }
                    }

                } else {
                    $info = $getFirstArgument();
                    if ($info) {
                        $argument = $info["id"];
                        $tag = $info["tag"];
                    }
                }
            ?>
            
            <select id="argumentSelector" onchange="updateInfos()">
                <?php
                    include_once(__DIR__. "/../Sources/Selectors.php");
                    $argumentselector($argument);
                ?>
            </select>

            <form method="post" id="mainForm">
                <input type="hidden" name="argument" value="<?php echo $argument; ?>" required>

                <table>
                    <tr><td class="field">Title:</td> <td><input type="textbox" name="tag" placeholder="An argument!" value="<?php echo $tag; ?>" required></td></tr>
                </table>

                <br><input type="submit" class="submit" name="save" value="Save">
                <br><input type="submit" class="submit" name="new" value="New">
                <br><input type="submit" class="submit" name="delete" value="Delete">
            </form>

            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Discard"/>
            </form>
        </div>
    </body>
</html>