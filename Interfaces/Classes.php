<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if(!$user)
    $redirect("Login.php");

//elseif (isset($_POST["back"]))
    //$redirect("Portal.php");

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
                document.getElementById("vclassInput").value = document.getElementById("vclassSelector").value

                if (loaded)
                    document.getElementById("classForm").submit()

                loaded = true
            }

            window.onload = updateClass
        </script>

        <form id="classForm" method="post">
            <input id="classInput" type="hidden" name="class">
            <input id="vclassInput" type="hidden" name="vclass">
        </form>

        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/classesStyle.css">
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
                $tag = "";

                $vclass = "";
                $vtag = "";

                if (isset($_POST["class"]))
                    $class = $secureSQL($_POST["class"]);
                
                else {
                    $info = $conn->query("
                        SELECT * 
                        FROM Classes 
                        ORDER BY tag ASC 
                        LIMIT 1
                    ");

                    if ($info->num_rows > 0) {
                        $assoc = $info->fetch_assoc();
                        $class = $assoc["id"];
                        $tag = $assoc["tag"];
                    }
                }

                if (isset($_POST["vclass"]))
                    $vclass = $secureSQL($_POST["vclass"]);
                
                else {
                    $info = $conn->query("
                        SELECT * 
                        FROM VirtualClasses 
                        ORDER BY tag ASC 
                        LIMIT 1
                    ");

                    if ($info->num_rows > 0) {
                        $assoc = $info->fetch_assoc();
                        $vclass = $assoc["id"];
                        $vtag = $assoc["tag"];
                    }
                }

                if (isset($_POST["add"])) {
                    $students = [];
                    if (isset($_POST["students"]))
                        foreach ($_POST["students"] as $student)
                            array_push($students, $secureSQL($student));

                    if (count($students) > 0) {
                        $query = "INSERT IGNORE INTO LinksUsers(student, virtualClass) VALUES";
                        foreach ($students as $student)
                            $query .= " (". $student .", ". $vclass ."),";

                        $query = substr_replace($query, '', -1);
                        $succ = $conn->query($query);

                        if (!$succ)
                            $error("Unable to add the student/s to the selected virtual class!");
                    }

                } elseif (isset($_POST["addAll"])) {
                    $users = $conn->query("
                        SELECT id 
                        FROM Students 
                        WHERE class = ". $class
                    );

                    $query = "INSERT IGNORE LinksUsers(student, virtualClass) VALUES";
                    if ($users->num_rows > 0) {
                        while($row = $users->fetch_assoc())
                            $query .= " (". $row["id"] .", ". $vclass ."),";

                        $query = substr_replace($query, '', -1);
                        $succ = $conn->query($query);
    
                        if (!$succ)
                            $error("Unable to add all the students to the selected virtual class!");
                    }

                } elseif (isset($_POST["remove"])) {
                    $vstudents = [];
                    if (isset($_POST["vstudents"]))
                        foreach ($_POST["vstudents"] as $vstudent)
                            array_push($vstudents, $secureSQL($vstudent));

                    if (count($vstudents) > 0) {
                        $res = $conn->query("
                            DELETE FROM LinksUsers 
                            WHERE student IN (". implode(',', $vstudents) .") AND virtualClass = $vclass
                        ");

                        if (!$res)
                            $error("Unable to remove the student/s to the selected virtual class!");
                    }
                }
            ?>

            <select id="classSelector" onchange="updateClass()">
                <?php $classselector($class); ?>
            </select>

            <form action="Class.php" method="get">
                <input type="submit" value="+">
            </form>

            <h3>Students</h3>
            <form method="post">
                <input type="hidden" name="vclass" value="<?php echo $vclass ?>">
                <input type="hidden" name="class" value="<?php echo $class ?>">

                <div class="scrollable">
                    <table>
                        <?php
                            if ($class != "") {
                                $users = $conn->query("
                                    SELECT *
                                    FROM Students 
                                    WHERE class = ". $class ." 
                                    ORDER BY surname ASC
                                ");

                                if ($users->num_rows > 0)
                                    while($row = $users->fetch_assoc())
                                        echo "<tr><td class='field'>". $row["surname"] ." ". $row["name"] ." - ". $row["username"] ."</td><td class='box'><input name='students[]' type='checkbox' value='". $row["id"] ."'></td></tr>";
                            }
                        ?>
                    </table>
                </div>

                <input type="submit" name="add" value="->">
                <input type="submit" name="addAll" value="->>">
            </form>

            <h3>Virtual Classes</h3>
            <select id="vclassSelector" onchange="updateClass()">
                <?php $vclassselector($vclass); ?>
            </select>

            <form action="VClass.php" method="get">
                <input type="submit" value="+">
            </form>

            <h3>Virtual Students</h3>
            <form method="post">
                <input type="hidden" name="vclass" value="<?php echo $vclass ?>">
                <input type="hidden" name="class" value="<?php echo $class ?>">

                <div class="scrollable">
                    <table>
                        <?php
                            if ($vclass != "") {
                                $users = $conn->query("
                                    SELECT s.surname, s.name, s.username, s.id
                                    FROM Students s 
                                    JOIN LinksUsers l ON s.id = l.student 
                                    JOIN VirtualClasses v ON l.virtualClass = v.id 
                                    WHERE v.id = ". $vclass ." 
                                    ORDER BY s.surname ASC
                                ");

                                if ($users->num_rows > 0)
                                    while($row = $users->fetch_assoc())
                                        echo "<tr><td class='field'>". $row["surname"] ." ". $row["name"] ." - ". $row["username"] ."</td><td class='box'><input name='vstudents[]' type='checkbox' value='". $row["id"] ."'></td></tr>";
                            }
                        ?>
                    </table>
                </div>

                <input type="submit" name="remove" value="x">
            </form>
            
            <form action="Portal.php" method="get">
                <input type="submit" class="submit" value="Back"/>
            </form>
        </div>
    </body>
</html>