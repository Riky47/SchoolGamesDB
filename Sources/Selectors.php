<?php
    if (!isset($conn))
        include_once(__DIR__. "/Connect.php");

    $classselector = function() use ($conn) {
        $result = $conn->query("SELECT id, tag FROM Classes ORDER BY tag ASC");
        while ($row = $result->fetch_assoc())
            echo "<option class='option' value=". $row["id"] .">". $row["tag"] ."</option>";
    };

    $argumentselector = function($arg) use ($conn) {
        $result = $conn->query("SELECT id, tag FROM Arguments ORDER BY tag ASC");
        while ($row = $result->fetch_assoc())
            echo "<option class='option' value=". $row["id"] .($arg == $row["id"] ? " selected" : ""). ">". $row["tag"] ."</option>";
    };
?>