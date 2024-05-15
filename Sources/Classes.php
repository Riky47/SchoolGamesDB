<?php
    if (!isset($conn))
        include_once(__DIR__. "/Connect.php");

    $classselector = function() use ($conn) {
        echo "<select class='class' name='class' required>";
        $result = $conn->query("SELECT tag FROM Classes ORDER BY tag ASC");

        while ($row = $result->fetch_assoc())
            echo "<option class='option' value=". $row["tag"] .">". $row["tag"] ."</option>";

        echo "</select>";
    };
?>