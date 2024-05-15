<?php
include_once(__DIR__. "/../Sources/Redirect.php");
include_once(__DIR__. "/../Sources/User.php");

$user = $getuser();
if ($user) {
    if ($user["type"] == "teacher")
        $redirect("Teacher.php");

} else
    $redirect("Login.php");
?>

<html>
    <head>
        <title>SchoolGamesDB</title>
        <link rel="stylesheet" href="Styles/RegisterStyle.css">
        <link rel="stylesheet" href="Styles/Default.css">
    </head>
    <body>
        <h1>SchoolGamesDB</h1>
        <div id="space"></div>

        <div id="leaderDiv">
            <h3>Welcome back <?php echo $user["name"] ?> !</h3>

            
        </div>
    </body>
</html>