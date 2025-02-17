<?php
    $host = "localhost";
    $db_name = "team2_aptech2";
    $name = "root";
    $password = "";

    $conn = new mysqli($host, $name, $password, $db_name);

    if ($conn->connect_error) {
        die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }
?>