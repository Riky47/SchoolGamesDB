<?php
    if (!isset($configs))
        include_once(__DIR__. "/../Configs.php");

    $conn = new mysqli ($configs["host"], $configs["dbuser"], $configs["dbpass"], $configs["dbname"]);
    if ($conn->connect_errno)
        die("Error on database connection: " . $conn->connect_error . "\n");
?>