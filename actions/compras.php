<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["amount"]) && isset($_POST["value"])) {
        
        echo "POST VALUES <br/>";
        echo $_POST["amount"] . "<br/>";
        echo $_POST["value"];
    }
}