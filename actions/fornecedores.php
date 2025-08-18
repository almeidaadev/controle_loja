<?php
include_once "../Dabase/Connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["action"] === "create") {
    $nome = $_POST['nome'];
    $documento = $_POST['documento'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $produtos = $_POST['produtos'];

    $sql = "INSERT INTO fornecedores (nome, documento, telefone, email, endereco, produtos)
            VALUES ('$nome','$documento','$telefone','$email','$endereco','$produtos')";
    $conn->query($sql);

    header("Location: ../Pages/Fornecedores.php");
    exit;
}

if (isset($_GET["action"]) && $_GET["action"] === "delete") {
    $id = $_GET["id"];
    $conn->query("DELETE FROM fornecedores WHERE id = $id");

    header("Location: ../Pages/Fornecedores.php");
    exit;
}
