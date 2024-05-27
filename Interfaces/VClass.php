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
            <h3>Virtual Classes</h3>
            <?php
                include_once(__DIR__. "/../Sources/SecureSQL.php");
                include_once(__DIR__. "/../Sources/Errors.php");

                $subject = "";
                $teacher = "";
                $class = "";
                $tag = "";

                $getFirstclass = function() use($conn, $error, $class, $tag) {
                    $info = $conn->query("
                        SELECT * 
                        FROM VirtualClasses 
                        ORDER BY tag ASC 
                        LIMIT 1
                    ");

                    if ($info->num_rows > 0)
                        return $info->fetch_assoc();
                    
                    return false;
                };

                $checkPresence = function($tag, $class) use($conn, $error) {
                    $res = $conn->query("
                        SELECT * 
                        FROM VirtualClasses 
                        WHERE tag = '". $tag ."' AND id != ". $class ."
                        LIMIT 1
                    ");

                    if ($res->num_rows > 0) {
                        $error("Title already in use!");
                        return true;
                    }

                    return false;
                };

                $getOtherInfos = function($class) use($conn, $error) {
                    $info = $conn->query("
                        SELECT t.username, v.subject 
                        FROM VirtualClasses v 
                        JOIN Teachers t ON v.teacher = t.id 
                        WHERE v.id = ". $class ." 
                        LIMIT 1
                    ");

                    if ($info->num_rows > 0)
                        return $info->fetch_assoc();
                    else
                        $error("Unable to retrive all the data!");

                    return false;
                };

                if (isset($_POST["class"])) {
                    $class = $secureSQL($_POST["class"]);

                    if (isset($_POST["tag"]))
                        $tag = $secureSQL($_POST["tag"]);

                    else {
                        $info = $conn->query("
                            SELECT tag 
                            FROM VirtualClasses 
                            WHERE id = ". $class ." 
                            LIMIT 1
                        ");

                        if ($info->num_rows > 0)
                            $tag = $info->fetch_assoc()["tag"];
                        else
                            $error("Unable to find the selected virtual class!");
                    }

                    if (isset($_POST["subject"]))
                        $subject = $secureSQL($_POST["subject"]);
                    else
                        $subject = "Unknown";

                    if (isset($_POST["save"])) {
                        if (!$checkPresence($tag, $class)) {
                            $res = $conn->query("
                                UPDATE VirtualClasses 
                                SET 
                                    tag = '". $tag ."', 
                                    subject = '". $subject ."' 
                                WHERE id = ". $class
                            );

                            if (!$res)
                                $error("Unable to save changes to the virtual class!");
                        }

                    } elseif (isset($_POST["delete"])) {
                        $res = $conn->query("
                            DELETE FROM VirtualClasses 
                            WHERE id = ". $class
                        );

                        if ($res) {
                            $info = $getFirstclass();
                            if ($info) {
                                $subject = $info["subject"];
                                $teacher = $info["teacher"];
                                $class = $info["id"];
                                $tag = $info["tag"];
                            }

                        } else
                            $error("Unable to delete the selected virtual class!");

                    } elseif (isset($_POST["new"])) {
                        if (!$checkPresence($tag, 0)) {
                            $res = $conn->query("
                                INSERT INTO VirtualClasses(tag, subject, teacher) 
                                VALUES ('". $tag ."', '". $subject ."', ". $user["id"]. ")
                            ");

                            if ($res)
                                $class = $conn->insert_id;
                            else
                                $error("Unable to create a new virtual class!");
                        }
                    }

                    if ($teacher == "" || $subject == "") {
                        $oinfo = $getOtherInfos($class);
                        if($oinfo) {
                            $teacher = $oinfo["username"];
                            $subject = $oinfo["subject"];
                        }
                    }

                } else {
                    $info = $getFirstclass();
                    if ($info) {
                        $subject = $info["subject"];
                        $class = $info["id"];
                        $tag = $info["tag"];

                        $oinfo = $getOtherInfos($class);
                        if($oinfo) {
                            $teacher = $oinfo["username"];
                            $subject = $oinfo["subject"];
                        }
                    }
                }
            ?>
            
            <select id="classSelector" onchange="updateInfos()">
                <?php
                    include_once(__DIR__. "/../Sources/Selectors.php");
                    $vclassselector($class);
                ?>
            </select>

            <form method="post" id="mainForm">
                <input type="hidden" name="class" value="<?php echo $class; ?>" required>

                <table>
                    <tr><td class="field">Title:</td> <td><input type="textbox" name="tag" placeholder="A class!" value="<?php echo $tag; ?>" required></td></tr>
                    <tr><td class="field">Subject:</td> <td><input type="textbox" name="subject" placeholder="A subject!" value="<?php echo $subject; ?>" required></td></tr>
                    <tr><td class="field">Teacher:</td> <td><input type="textbox" name="teacher" placeholder="A teacher!" value="<?php echo $teacher; ?>" readonly></td></tr>
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