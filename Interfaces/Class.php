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
                document.getElementById("classInput").value = document.getElementById("classSelector").value
                if (loaded)
                    document.getElementById("updateForm").submit()

                loaded = true
            }

            window.onload = updateInfos
        </script>

        <form id="updateForm" method="post">
            <input id="classInput" type="hidden" name="class">
        </form>

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
                include_once(__DIR__. "/../Sources/Errors.php");

                $class = "";
                $tag = "";

                $getFirstclass = function() use($conn, $error, $class, $tag) {
                    $info = $conn->query("
                        SELECT * 
                        FROM Classes 
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
                        FROM Classes 
                        WHERE tag = '". $tag ."' 
                        LIMIT 1
                    ");

                    if ($res->num_rows > 0) {
                        $error("Title already in use!");
                        return true;
                    }

                    return false;
                };

                if (isset($_POST["class"])) {
                    $class = $secureSQL($_POST["class"]);

                    if (isset($_POST["tag"]))
                        $tag = $secureSQL($_POST["tag"]);

                    else {
                        $info = $conn->query("
                            SELECT tag 
                            FROM Classes 
                            WHERE id = ". $class ." 
                            LIMIT 1
                        ");

                        if ($info->num_rows > 0)
                            $tag = $info->fetch_assoc()["tag"];
                        else
                            $error("Unable to find the selected class!");
                    }

                    if (isset($_POST["save"])) {
                        if (!$checkPresence($tag)) {
                            $res = $conn->query("
                                UPDATE Classes 
                                SET 
                                    tag = '". $tag ."' 
                                WHERE id = ". $class
                            );

                            if (!$res)
                                $error("Unable to save changes to the class!");
                        }

                    } elseif (isset($_POST["delete"])) {
                        $res = $conn->query("
                            DELETE FROM Classes 
                            WHERE id = ". $class
                        );

                        if ($res) {
                            $info = $getFirstclass();
                            if ($info) {
                                $class = $info["id"];
                                $tag = $info["tag"];
                            }

                        } else
                            $error("Unable to delete the selected class!");

                    } elseif (isset($_POST["new"])) {
                        if (!$checkPresence($tag)) {
                            $res = $conn->query("
                                INSERT INTO Classes(tag) 
                                VALUES ('". $tag ."')
                            ");

                            if ($res)
                                $class = $conn->insert_id;
                            else
                                $error("Unable to create a new class!");
                        }
                    }

                } else {
                    $info = $getFirstclass();
                    if ($info) {
                        $class = $info["id"];
                        $tag = $info["tag"];
                    }
                }
            ?>
            
            <select id="classSelector" onchange="updateInfos()">
                <?php
                    include_once(__DIR__. "/../Sources/Selectors.php");
                    $classselector($class);
                ?>
            </select>

            <form method="post" id="mainForm">
                <input type="hidden" name="class" value="<?php echo $class; ?>" required>

                <table>
                    <tr><td class="field">Title:</td> <td><input type="textbox" name="tag" placeholder="A class!" value="<?php echo $tag; ?>" required></td></tr>
                </table>

                <br><input type="submit" class="submit" name="save" value="Save">
                <br><input type="submit" class="submit" name="new" value="New">
                <br><input type="submit" class="submit" name="delete" value="Delete">
            </form>

            <form action="Classes.php" method="get">
                <input type="submit" class="submit" value="Back"/>
            </form>
        </div>
    </body>
</html>