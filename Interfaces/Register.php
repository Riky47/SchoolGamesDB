<?php
include_once(__DIR__. "/../Sources/Connect.php");

if (isset($_POST["register"])) {
    include_once(__DIR__. "/../Sources/SecureSQL.php");
    
    $isStudents = $_POST["type"] == "student";
    $conn->query("INSERT INTO ". (isStudents ? "Students" : "Teachers") ."(name, surname, username, email, password". (isStudents ? ", class" : "") .") VALUES ('".
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

<!DOCTYPE html>
<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="RegisterStyle.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <h3>Register</h3>

        <form method="post" action="/Interfaces/Register.php" id="mainForm">
            Type: <select name="type" id="typeSwitch" selected="student" onchange="updateType()" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select><br><br>

            Name: <input type="text" name="name" placeholder="Mario" required><br>
            Surname: <input type="text" name="surname" placeholder="Rossi" required><br>
            Nickname: <input type="text" name="username" placeholder="MarRosso" required><br>
            EMail: <input type="email" name="email" placeholder="mario.rossi@email.it" required><br>
            Password: <input type="password" name="password" placeholder="M****R****1!" required><br>
        </form>
    </body>
</html>