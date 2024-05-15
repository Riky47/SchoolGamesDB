<?php
    if (session_status() !== PHP_SESSION_ACTIVE)
        session_start();

    if (!isset($conn))
        include_once(__DIR__. "/Connect.php");

    $getuser = function() use ($conn) {
        if (isset($_SESSION["isStudent"])) {
            $isStudent = $_SESSION["isStudent"];
            $user = $conn->query("SELECT * FROM ". ($isStudent ? "Students" : "Teachers") ." WHERE id = ". $_SESSION["userId"]);

            if ($user->num_rows > 0) {
                $assoc = $user->fetch_assoc();
                $assoc["isStudent"] = $isStudent;
                return $assoc;
            } 
        }

        return false;
    };
?>