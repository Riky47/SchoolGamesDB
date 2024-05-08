<?php include_once(__DIR__. "/../Sources/Connect.php") ?>

<!DOCTYPE html>
<html>
    <head>
        <title>SchoolGamesDB</title>
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <h3>Register</h3>

        <form method="post" action="">
            Name: <input type="text" name="name" placeholder="Mario" required><br>
            Surname: <input type="text" name="surname" placeholder="Rossi" required><br>
            Type: <select type="text" name="type">
                <option value="Student">Student</option>
                <option value="Teacher">Teacher</option>
            </select>
            <submit type="submit" name="register">
        </form>
    </body>
</html>