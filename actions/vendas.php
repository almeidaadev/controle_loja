<?php
header('Content-Type: application/json; charset=utf-8');
require "../Database/Connection.php";

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case 'POST':
        $produto    = trim($_POST["produto"]    ?? "");
        $quantidade = trim($_POST["quantidade"] ?? "");
        $unidade    = trim($_POST["unidade"]    ?? "");
        $desconto   = trim($_POST["desconto"]   ?? "");

        if (empty($produto) || empty($quantidade) || empty($unidade) || empty($desconto)) {
            echo json_encode(["message_error" => "Todos os campos precisam estar preenchidos!"]);
            exit;
        }

        if (!is_numeric($quantidade) || !is_numeric($unidade) || !is_numeric($desconto)) {
            echo json_encode(["message_error" => "Quantidade, unidade e desconto devem ser numéricos."]);
            exit;
        }

        $stmt = $conn->prepare(
            "INSERT INTO venda (produto, quantidade, valor_unidade, valor_desconto) VALUES (?, ?, ?, ?)"
        );

        $qtd  = (int)   $quantidade;
        $uni  = (float) $unidade;
        $desc = (float) $desconto;

        $stmt->bind_param("sidd", $produto, $qtd, $uni, $desc);

        if ($stmt->execute()) {
            echo json_encode(["message_success" => "Produto cadastrado com sucesso!"]);
        } else {
            echo json_encode(["message_error" => "Erro ao cadastrar: " . $stmt->error]);
        }

        $stmt->close();
        break;

    case 'GET':
        // futura listagem
        break;

    default:
        http_response_code(405);
        echo json_encode(["message_error" => "Método não permitido."]);
        break;
}
