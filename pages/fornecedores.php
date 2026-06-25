<?php include_once "../Dabase/Connection.php"; ?>

<div class="container__fornecedores">
    <!-- Formulário -->
    <form action="/controle_loja/actions/fornecedores.php" method="POST" class="form">
        <h2>Cadastrar Fornecedor</h2>

        <label>Nome:</label>
        <input type="text" name="nome" required><br>

        <label>CNPJ/CPF:</label>
        <input type="text" name="documento"><br>

        <label>Telefone:</label>
        <input type="text" name="telefone"><br>

        <label>Email:</label>
        <input type="email" name="email"><br>

        <label>Endereço:</label>
        <input type="text" name="endereco"><br>

        <label>Produtos fornecidos:</label>
        <textarea name="produtos"></textarea><br>

        <button type="submit" name="action" value="create">Salvar</button>
    </form>

    <!-- Lista -->
    <div class="lista">
        <h2>Fornecedores Cadastrados</h2>
        <table>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM fornecedores");
            while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['nome'] ?></td>
                <td><?= $row['telefone'] ?></td>
                <td><?= $row['email'] ?></td>
                <td>
                    <a href="/controle_loja/actions/fornecedores.php?action=delete&id=<?= $row['id'] ?>">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
