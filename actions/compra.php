<?php
header('Content-Type: application/json; charset=utf-8');
require "../Database/Connection.php";

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case 'POST':
        $produto    = trim($_POST["produto"]    ?? "");
        $fornecedor = trim($_POST["fornecedor"] ?? "");
        $quantidade = trim($_POST["quantidade"] ?? "");
        $unidade    = trim($_POST["unidade"]    ?? "");
        $desconto   = trim($_POST["desconto"]   ?? "");
        $taxas      = trim($_POST["taxas"]      ?? "");

        // Validação de campos obrigatórios
        if (empty($produto) || empty($fornecedor) || empty($quantidade) || empty($unidade)) {
            echo json_encode(["message_error" => "Produto, fornecedor, quantidade e unidade são obrigatórios."]);
            exit;
        }

        // Validação de tipos numéricos
        if (!is_numeric($quantidade) || !is_numeric($unidade)) {
            echo json_encode(["message_error" => "Quantidade e valor unitário devem ser numéricos."]);
            exit;
        }

        if (!empty($desconto) && !is_numeric($desconto)) {
            echo json_encode(["message_error" => "Desconto deve ser numérico."]);
            exit;
        }

        if (!empty($taxas) && !is_numeric($taxas)) {
            echo json_encode(["message_error" => "Taxas devem ser numéricas."]);
            exit;
        }

        // Cast de tipos
        $qtd        = (int)   $quantidade;
        $uni        = (float) $unidade;
        $desc       = (float) ($desconto ?: 0);
        $tax        = (float) ($taxas    ?: 0);

        // Valor total: (quantidade * unidade) - desconto + taxas
        $valor_total = ($qtd * $uni) - $desc + $tax;

        $stmt = $conn->prepare("
            INSERT INTO compra 
                (produto, fornecedor, quantidade, valor_unidade, valor_desconto, taxas, valor_total, data_compra) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        // s = string | i = integer | d = double
        $stmt->bind_param("ssidddd", $produto, $fornecedor, $qtd, $uni, $desc, $tax, $valor_total);

        if ($stmt->execute()) {
            echo json_encode([
                "message_success" => "Compra registrada com sucesso!",
                "data" => [
                    "produto"      => $produto,
                    "fornecedor"   => $fornecedor,
                    "quantidade"   => $qtd,
                    "valor_unit"   => $uni,
                    "desconto"     => $desc,
                    "taxas"        => $tax,
                    "valor_total"  => $valor_total,
                ]
            ]);
        } else {
            echo json_encode(["message_error" => "Erro ao registrar compra: " . $stmt->error]);
        }

        $stmt->close();
        break;

    case 'GET':
        $stmt = $conn->prepare("
            SELECT id, produto, fornecedor, quantidade, valor_unidade, 
                   valor_desconto, taxas, valor_total, data_compra 
            FROM compra 
            ORDER BY data_compra DESC
        ");

        $stmt->execute();
        $result = $stmt->get_result();

        $compras = [];
        while ($row = $result->fetch_assoc()) {
            $compras[] = $row;
        }

        echo json_encode([
            "total"   => count($compras),
            "compras" => $compras
        ]);

        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["message_error" => "Método não permitido."]);
        break;
}