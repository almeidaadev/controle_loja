<navigation class="navigation">
    <h1 style="color: white;">Hora atual: <span id="relogio"></span></h1>
    <ul>
        <li><a href="?page=compras">Compras</a></li>
        <li><a href="?page=vendas">Vendas</a></li>
        <li><a href="?page=resumo">Resumo</a></li>
        <li><a href="?page=fornecedores">Fornecedores</a></li>
    </ul>

    <script>
        function atualizarRelogio() {
            const agora = new Date(); // Pega a hora do sistema do usuário
            const horas = agora.getHours().toString().padStart(2, '0');
            const minutos = agora.getMinutes().toString().padStart(2, '0');
            const segundos = agora.getSeconds().toString().padStart(2, '0');

            const horaFormatada = `${horas}:${minutos}:${segundos}`;
            document.getElementById("relogio").textContent = horaFormatada;
        }

        // Atualiza o relógio imediatamente e depois a cada segundo
        atualizarRelogio();
        setInterval(atualizarRelogio, 1000);
    </script>
</navigation>