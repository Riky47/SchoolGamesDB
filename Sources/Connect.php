<?php
    $conn = new mysqli ("localhost", "root", "", "SchoolGamesDB");
    if ($conn->connect_errno)
        die("Error on database connection: " . $conn->connect_error . "\n");
?>