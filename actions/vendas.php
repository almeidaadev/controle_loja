<?php
header('Content-Type: application/json; charset=utf-8');


$requestMethod = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"):
    $requestMethod = "POST";
else:
    $requestMethod = "GET";
endif;

switch ($requestMethod) {
    case 'POST':
        $produto = $_POST["produto"] ?? "";
        $quantidade = $_POST["quantidade"] ?? "";
        $unidade = $_POST["unidade"] ?? "";
        $desconto = $_POST["desconto"] ?? "";

        if (empty($produto) || empty($quantidade) || empty($unidade) || empty($desconto)) echo json_encode(["message_error" => "Todos os campos precisam estar preenchidos!."]);

        

        break;
    case 'GET':
        # code...
        break;

    default:
        # code...
        break;
}
