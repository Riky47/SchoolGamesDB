<?php
    include_once(__DIR__. "/../Sources/Connect.php");
    
    if (isset($_POST["register"])) {
        echo "DIOPOR";
        include_once(__DIR__. "/../Sources/SecureSQL.php");
        
        $isStudents = $_POST["type"] == "student";
        $conn->query("INSERT INTO ". ($isStudents ? "Students" : "Teachers") ."(name, surname, username, email, password". ($isStudents ? ", class" : "") .") VALUES ('".
            $secureSQL($_POST["name"])      ."', '".
            $secureSQL($_POST["surname"])   ."', '". 
            $secureSQL($_POST["username"])  ."', '".
            $secureSQL($_POST["email"])     ."', '". 
            $secureSQL($_POST["password"])  ."', ". 
            "(SELECT id FROM Classes WHERE tag = '". $secureSQL($_POST["class"]) ."'));"
        );

        // To review
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <script>
            var ogHtml
            var form
            var last

            function updateType() {
                var value = document.getElementById("typeSwitch").value
                if (last == value)
                    return

                var html = ogHtml
                last = value

                if (value == "student")
                    html += `Class: <select name="class" required>
                    <?php
                        $result = $conn->query("SELECT tag FROM Classes");
                        while ($row = $result->fetch_assoc())
                            echo "<option value=". $row["tag"] .">". $row["tag"] ."</option>";
                    ?>
                    </select><br>`

                form.innerHTML = html + `<br><input type="submit" name="register" value="Register">`
                var select = document.getElementById("typeSwitch")
                select.value = value
            }
            
            function load() {
                form = document.getElementById("mainForm")
                ogHtml = form.innerHTML
                
                updateType()
            }

            window.onload = load
        </script>

        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <div id="leaderDiv">
            <h3>Register</h3>
            <form method="POST" id="mainForm">
                Type: <select name="type" id="typeSwitch" onchange="updateType()" required>
                    <option value="student" selected>Student</option>
                    <option value="teacher">Teacher</option>
                </select><br><br>

                Name: <input type="text" name="name" placeholder="Mario" required><br>
                Surname: <input type="text" name="surname" placeholder="Rossi" required><br>
                Nickname: <input type="text" name="username" placeholder="MarRosso" required><br>
                EMail: <input type="email" name="email" placeholder="mario.rossi@email.it" required><br>
                Password: <input type="password" name="password" placeholder="M****R****1!" required><br>
            </form>
        </div>
    </body>
</html>