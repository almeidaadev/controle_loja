<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "loja";

try {
    $conn = new mysqli($host, $username, $password, $database);
    echo "Connected successfully!";
} catch (mysqli_sql_exception $e) {
    exit("Database connection failed. Please try again later.");
}
