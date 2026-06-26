<?php
header('Content-Type: application/json; charset=utf-8');
require "../Database/Connection.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["message_error" => "Método não permitido."]);
    exit;
}

// ── Período ─────────────────────────────────────────────────────
$periodo = $_GET["periodo"] ?? "mes_atual";
$hoje    = date("Y-m-d");

switch ($periodo) {
    case "hoje":
        $de  = $hoje;
        $ate = $hoje;
        break;
    case "7dias":
        $de  = date("Y-m-d", strtotime("-7 days"));
        $ate = $hoje;
        break;
    case "30dias":
        $de  = date("Y-m-d", strtotime("-30 days"));
        $ate = $hoje;
        break;
    case "mes_atual":
        $de  = date("Y-m-01");
        $ate = date("Y-m-t");
        break;
    case "ano_atual":
        $de  = date("Y-01-01");
        $ate = date("Y-12-31");
        break;
    case "personalizado":
        $de  = $_GET["de"]  ?? date("Y-m-01");
        $ate = $_GET["ate"] ?? $hoje;

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $de) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $ate)) {
            echo json_encode(["message_error" => "Datas inválidas. Use YYYY-MM-DD."]);
            exit;
        }
        if ($de > $ate) {
            echo json_encode(["message_error" => "Data inicial não pode ser maior que a final."]);
            exit;
        }
        break;
    default:
        echo json_encode(["message_error" => "Período inválido. Use: hoje, 7dias, 30dias, mes_atual, ano_atual, personalizado."]);
        exit;
}

// ── Vendas ──────────────────────────────────────────────────────
// valor_total da venda = (quantidade * valor_unidade) - valor_desconto
$stmt = $conn->prepare("
    SELECT
        COUNT(*)                                                        AS total_vendas,
        COALESCE(SUM(quantidade), 0)                                    AS total_itens_vendidos,
        COALESCE(SUM((quantidade * valor_unidade) - COALESCE(valor_desconto, 0)), 0) AS receita_total
    FROM venda
    WHERE vendido_at BETWEEN ? AND ?
");
$stmt->bind_param("ss", $de, $ate);
$stmt->execute();
$vendas = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Vendas por dia (para gráfico de linha)
$stmt = $conn->prepare("
    SELECT
        vendido_at                                                              AS dia,
        COUNT(*)                                                                AS qtd_vendas,
        COALESCE(SUM((quantidade * valor_unidade) - COALESCE(valor_desconto,0)), 0) AS receita
    FROM venda
    WHERE vendido_at BETWEEN ? AND ?
    GROUP BY vendido_at
    ORDER BY vendido_at ASC
");
$stmt->bind_param("ss", $de, $ate);
$stmt->execute();
$result = $stmt->get_result();
$grafico_vendas = [];
while ($row = $result->fetch_assoc()) {
    $grafico_vendas[] = $row;
}
$stmt->close();

// ── Compras ─────────────────────────────────────────────────────
$stmt = $conn->prepare("
    SELECT
        COUNT(*)                          AS total_compras,
        COALESCE(SUM(quantidade), 0)      AS total_itens_comprados,
        COALESCE(SUM(valor_total), 0)     AS custo_total
    FROM compra
    WHERE DATE(data_compra) BETWEEN ? AND ?
");
$stmt->bind_param("ss", $de, $ate);
$stmt->execute();
$compras = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ── Fornecedores (total geral, sem filtro de período) ────────────
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM fornecedores");
$stmt->execute();
$fornecedores = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ── Lucro ────────────────────────────────────────────────────────
$lucro = (float) $vendas["receita_total"] - (float) $compras["custo_total"];

// ── Resposta ─────────────────────────────────────────────────────
echo json_encode([
    "periodo" => [
        "tipo" => $periodo,
        "de"   => $de,
        "ate"  => $ate,
    ],
    "vendas" => [
        "total_vendas"        => (int)   $vendas["total_vendas"],
        "total_itens_vendidos" => (int)   $vendas["total_itens_vendidos"],
        "receita_total"       => (float) $vendas["receita_total"],
    ],
    "compras" => [
        "total_compras"        => (int)   $compras["total_compras"],
        "total_itens_comprados" => (int)   $compras["total_itens_comprados"],
        "custo_total"          => (float) $compras["custo_total"],
    ],
    "fornecedores" => [
        "total" => (int) $fornecedores["total"],
    ],
    "lucro"          => $lucro,
    "grafico_vendas" => $grafico_vendas,
]);
