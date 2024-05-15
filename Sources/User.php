<?php
    if (session_status() !== PHP_SESSION_ACTIVE)
        session_start();

    if (!isset($conn))
        include_once(__DIR__. "/Connect.php");

    $setuser = function($id, $type) {
        $_SESSION["userType"] = $type;
        $_SESSION["userId"] = $id;
    };

    $getuser = function() use ($conn) {
        if (isset($_SESSION["userType"])) {
            $isStudent = $_SESSION["userType"] == "student";
            $user = $conn->query("SELECT * FROM ". ($isStudent ? "Students" : "Teachers") ." WHERE id = ". $_SESSION["userId"]);

            if ($user->num_rows > 0) {
                $assoc = $user->fetch_assoc();
                $assoc["type"] = $_SESSION["userType"];

                return $assoc;
            } 
        }

        return false;
    };

    $removeuser = function() {
        session_destroy();
    }
?>