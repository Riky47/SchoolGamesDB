<!DOCTYPE html>

<html>
    <head>
        <script>
            var ogHtml
            var table
            var last

            function updateType() {
                var value = document.getElementById("typeSwitch").value
                if (last == value)
                    return

                var html = ogHtml
                last = value

                if (value == "student")
                    html += `<tr><td class="field">Class:</td> <td class="box">
                    <?php
                        include_once(__DIR__. "/../Sources/Classes.php");
                        $classselector();
                    ?>
                    <td></tr>`

                table.innerHTML = html
                var select = document.getElementById("typeSwitch")
                select.value = value
            }
            
            function load() {
                table = document.getElementById("mainTable")
                ogHtml = table.innerHTML
                
                updateType()
            }

            window.onload = load
        </script>

        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>

    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Register</h3>
            <?php
                if (isset($_POST["register"])) {
                    include_once(__DIR__. "/../Sources/SecureSQL.php");
                    include_once(__DIR__. "/../Sources/Redirect.php");
                    include_once(__DIR__. "/../Sources/Errors.php");
                    
                    $student = $conn->query("SELECT * FROM Students WHERE email = '". $secureSQL($_POST["email"]) ."' OR username = '". $secureSQL($_POST["username"]) ."';");
                    $teacher = $conn->query("SELECT * FROM Teachers WHERE email = '". $secureSQL($_POST["email"]) ."' OR username = '". $secureSQL($_POST["username"]) ."';");
                    
                    if ($student->num_rows > 0 || $teacher->num_rows > 0)
                        $error("Credentials already in use!");

                    else {
                        $isStudents = $_POST["type"] == "student";
                        $result = $conn->query("INSERT INTO ". ($isStudents ? "Students" : "Teachers") ."(name, surname, username, email, password". ($isStudents ? ", class" : "") .") VALUES ('".
                            $secureSQL($_POST["name"])      ."', '".
                            $secureSQL($_POST["surname"])   ."', '". 
                            $secureSQL($_POST["username"])  ."', '".
                            $secureSQL($_POST["email"])     ."', '". 
                            password_hash($_POST["password"], PASSWORD_ARGON2I) ."'".
                            ($isStudents ? (", (SELECT id FROM Classes WHERE tag = '". $secureSQL($_POST["class"]) ."')") : "") .");"
                        );

                        if ($result)
                            $redirect("Login.php");
                        else
                            $error("Error, please contact a developer!");
                    }
                }
            ?>

            <form method="post" id="mainForm">
                <table id="mainTable">
                    <tr><td class="field">Type:</td>
                    <td class="box"><select name="type" id="typeSwitch" onchange="updateType()" required>
                        <option value="student" selected>Student</option>
                        <option value="teacher">Teacher</option>
                    </select></td></tr>

                    <tr><td class="field">Name:</td> <td class="box"><input type="text" name="name" placeholder="Mario" required></td></tr>
                    <tr><td class="field">Surname:</td> <td class="box"><input type="text" name="surname" placeholder="Rossi" required></td></tr>
                    <tr><td class="field">Nickname:</td> <td class="box"><input type="text" name="username" placeholder="MarRosso" required></td></tr>
                    <tr><td class="field">EMail:</td> <td class="box"><input type="email" name="email" placeholder="mario.rossi@email.it" required></td></tr>
                    <tr><td class="field">Password:</td> <td class="box"><input type="password" name="password" placeholder="M****R****1!" required></td></tr>
                </table>

                <br><input type="submit" class="submit" name="register" value="Register">
            </form>

            <form action="Login.php" method="get">
                <input type="submit" class="submit" value="Sign-In"/>
            </form>
        </div>
    </body>
</html>