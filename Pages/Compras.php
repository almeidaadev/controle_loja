<div class="container__compra">
    <h2>Registrar Compra</h2>
    <br />

    <form action="compras.php" method="POST">
        <label>Data da compra:</label>
        <input type="date" name="data_compra" required><br><br>

        <label>Produto:</label>
        <input type="text" name="produto" required><br><br>

        <label>Quantidade:</label>
        <input type="number" name="quantidade" required><br><br>

        <label>Valor unitário:</label>
        <input type="number" step="0.01" name="valor_unitario" required><br><br>

        <label>Fornecedor:</label>
        <input type="text" name="fornecedor"><br><br>

        <label>Forma de pagamento:</label>
        <select name="pagamento">
            <option>Dinheiro</option>
            <option>Cartão</option>
            <option>PIX</option>
        </select><br><br>

        <label>Observações:</label><br>
        <textarea name="observacoes"></textarea><br><br>

        <button type="submit">Salvar Compra</button>
    </form>

</div>