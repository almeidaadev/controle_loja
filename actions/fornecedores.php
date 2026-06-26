<?php
header('Content-Type: application/json; charset=utf-8');
require "../Database/Connection.php";

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case 'POST':
        $action = trim($_POST["action"] ?? "create");

        // ── UPDATE ──────────────────────────────────────────────
        if ($action === "update") {
            $id        = (int) ($_POST["id"] ?? 0);
            $nome      = trim($_POST["nome"]      ?? "");
            $documento = trim($_POST["documento"] ?? "");
            $telefone  = trim($_POST["telefone"]  ?? "");
            $email     = trim($_POST["email"]     ?? "");
            $endereco  = trim($_POST["endereco"]  ?? "");
            $produtos  = trim($_POST["produtos"]  ?? "");

            if ($id <= 0) {
                echo json_encode(["message_error" => "ID inválido."]);
                exit;
            }

            if (empty($nome) || empty($documento)) {
                echo json_encode(["message_error" => "Nome e documento são obrigatórios."]);
                exit;
            }

            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["message_error" => "E-mail inválido."]);
                exit;
            }

            $stmt = $conn->prepare("
                UPDATE fornecedores 
                SET nome=?, documento=?, telefone=?, email=?, endereco=?, produtos=?
                WHERE id=?
            ");
            $stmt->bind_param("ssssssi", $nome, $documento, $telefone, $email, $endereco, $produtos, $id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows === 0) {
                    echo json_encode(["message_error" => "Nenhum fornecedor encontrado com esse ID."]);
                } else {
                    echo json_encode(["message_success" => "Fornecedor atualizado com sucesso!"]);
                }
            } else {
                echo json_encode(["message_error" => "Erro ao atualizar: " . $stmt->error]);
            }

            $stmt->close();
            exit;
        }

        // ── CREATE (default) ────────────────────────────────────
        $nome      = trim($_POST["nome"]      ?? "");
        $documento = trim($_POST["documento"] ?? "");
        $telefone  = trim($_POST["telefone"]  ?? "");
        $email     = trim($_POST["email"]     ?? "");
        $endereco  = trim($_POST["endereco"]  ?? "");
        $produtos  = trim($_POST["produtos"]  ?? "");

        if (empty($nome) || empty($documento)) {
            echo json_encode(["message_error" => "Nome e documento são obrigatórios."]);
            exit;
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["message_error" => "E-mail inválido."]);
            exit;
        }

        $stmt = $conn->prepare("
            INSERT INTO fornecedores (nome, documento, telefone, email, endereco, produtos)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssss", $nome, $documento, $telefone, $email, $endereco, $produtos);

        if ($stmt->execute()) {
            echo json_encode([
                "message_success" => "Fornecedor cadastrado com sucesso!",
                "data" => [
                    "id"        => $conn->insert_id,
                    "nome"      => $nome,
                    "documento" => $documento,
                    "telefone"  => $telefone,
                    "email"     => $email,
                    "endereco"  => $endereco,
                    "produtos"  => $produtos,
                ]
            ]);
        } else {
            // Erro de documento duplicado
            if ($conn->errno === 1062) {
                echo json_encode(["message_error" => "Documento já cadastrado."]);
            } else {
                echo json_encode(["message_error" => "Erro ao cadastrar: " . $stmt->error]);
            }
        }

        $stmt->close();
        break;

    // ── GET (listar / buscar por ID) ────────────────────────────
    case 'GET':
        $id = (int) ($_GET["id"] ?? 0);

        if ($id > 0) {
            // Busca fornecedor específico
            $stmt = $conn->prepare("SELECT * FROM fornecedores WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $fornecedor = $result->fetch_assoc();

            if ($fornecedor) {
                echo json_encode($fornecedor);
            } else {
                http_response_code(404);
                echo json_encode(["message_error" => "Fornecedor não encontrado."]);
            }

            $stmt->close();
            break;
        }

        // Lista todos
        $stmt = $conn->prepare("SELECT * FROM fornecedores ORDER BY nome ASC");
        $stmt->execute();
        $result = $stmt->get_result();

        $fornecedores = [];
        while ($row = $result->fetch_assoc()) {
            $fornecedores[] = $row;
        }

        echo json_encode([
            "total"        => count($fornecedores),
            "fornecedores" => $fornecedores
        ]);

        $stmt->close();
        break;

    // ── DELETE ──────────────────────────────────────────────────
    case 'DELETE':
        // Lê o body da requisição (fetch/axios enviam JSON no DELETE)
        $body = json_decode(file_get_contents("php://input"), true);
        $id   = (int) ($body["id"] ?? 0);

        if ($id <= 0) {
            echo json_encode(["message_error" => "ID inválido."]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM fornecedores WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows === 0) {
                http_response_code(404);
                echo json_encode(["message_error" => "Fornecedor não encontrado."]);
            } else {
                echo json_encode(["message_success" => "Fornecedor removido com sucesso!"]);
            }
        } else {
            echo json_encode(["message_error" => "Erro ao remover: " . $stmt->error]);
        }

        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["message_error" => "Método não permitido."]);
        break;
}
