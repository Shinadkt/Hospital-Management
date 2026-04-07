<?php
$conn = new mysqli("localhost", "root", "", "hospital_db");
if ($conn->connect_error) die("DB Error");
$conn->set_charset("utf8mb4");
?>