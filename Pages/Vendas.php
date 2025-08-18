<div class="container__vendas">
    <h2>Registrar Venda</h2>
    <br>
    <form action="vendas.php" method="POST">
        <label>Data da venda:</label>
        <input type="date" name="data_venda" required><br><br>

        <label>Produto:</label>
        <input type="text" name="produto" required><br><br>

        <label>Quantidade:</label>
        <input type="number" name="quantidade" required><br><br>

        <label>Valor unitário:</label>
        <input type="number" step="0.01" name="valor_unitario" required><br><br>

        <label>Forma de pagamento:</label>
        <select name="pagamento">
            <option>Dinheiro</option>
            <option>Cartão</option>
            <option>PIX</option>
        </select><br><br>

        <label>Cliente:</label>
        <input type="text" name="cliente"><br><br>

        <button type="submit">Salvar Venda</button>
    </form>
</div>