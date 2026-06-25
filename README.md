# 🏪 Controle de Loja

Sistema de gerenciamento comercial desenvolvido em PHP puro com MySQL, focado no controle de compras, vendas, fornecedores e resumo financeiro do negócio.

## 📋 Funcionalidades

- **Compras** — registro de entradas de estoque com data, produto, quantidade, valor, fornecedor e forma de pagamento
- **Vendas** — registro de saídas com data, produto, quantidade, valor unitário, forma de pagamento (Dinheiro, Cartão, PIX) e cliente
- **Fornecedores** — cadastro completo com nome, CNPJ/CPF, telefone, e-mail, endereço e produtos fornecidos
- **Resumo financeiro** — painel com total de compras, vendas, lucro bruto, despesas e lucro líquido
- **Relógio em tempo real** na barra lateral de navegação

## 🛠 Tecnologias

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP 8+ |
| Banco de dados | MySQL / MariaDB |
| Frontend | HTML, CSS (variáveis customizadas), JavaScript |
| Lib JS | jQuery 3.7.1 |
| Padrão de conexão | Singleton (PDO-ready) via classe `Connection` |

## 📂 Estrutura do Projeto

```
controle_loja/
├── Database/
│   └── Connection.php       # Conexão Singleton com o MySQL
├── actions/                 # Lógica de backend (POST/GET handlers)
│   ├── compras.php
│   ├── fornecedores.php
│   ├── resumo.php
│   └── vendas.php
├── css/                     # Estilos modulares
│   ├── variables.css
│   ├── reset.css
│   ├── style.css
│   ├── navigation.css
│   └── forncedores.css
├── pages/                   # Views de cada módulo
│   ├── compras.php
│   ├── fornecedores.php
│   ├── resumo.php
│   └── vendas.php
├── parts/                   # Componentes reutilizáveis
│   ├── header.php
│   ├── footer.php
│   └── navigationSide.php
├── Scripts/
│   └── jquery-3.7.1.min.js
├── index.php                # Roteador principal
├── ajax.php                 # Endpoint AJAX
└── index.js
```

## ⚙️ Como rodar localmente

### Pré-requisitos

- PHP 8.0 ou superior
- MySQL 5.7+ ou MariaDB
- Servidor web local: [XAMPP](https://www.apachefriends.org/), [Laragon](https://laragon.org/) ou similar

### Passo a passo

```bash
# 1. Clone o repositório
git clone https://github.com/almeidaadev/controle_loja.git

# 2. Mova para a pasta do servidor (ex: XAMPP)
mv controle_loja /xampp/htdocs/

# 3. Acesse o phpMyAdmin e crie o banco de dados
```

```sql
CREATE DATABASE store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE store;

CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    documento VARCHAR(20),
    telefone VARCHAR(20),
    email VARCHAR(100),
    endereco VARCHAR(200),
    produtos TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_compra DATE NOT NULL,
    produto VARCHAR(100) NOT NULL,
    quantidade INT NOT NULL,
    valor_unitario DECIMAL(10,2) NOT NULL,
    fornecedor VARCHAR(100),
    pagamento VARCHAR(20),
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_venda DATE NOT NULL,
    produto VARCHAR(100) NOT NULL,
    quantidade INT NOT NULL,
    valor_unitario DECIMAL(10,2) NOT NULL,
    pagamento VARCHAR(20),
    cliente VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

```bash
# 4. Acesse no navegador
http://localhost/controle_loja/
```

> **Nota:** as credenciais do banco estão em `Database/Connection.php`. Por padrão são `root` sem senha e banco `store`. Ajuste conforme seu ambiente.

## 🔌 Configuração do banco

Edite o arquivo `Database/Connection.php` e altere as constantes:

```php
private const HOST = "localhost";
private const USER = "root";
private const PASS = "";
private const DB   = "store";
```

## 🚧 Status do projeto

Em desenvolvimento ativo. Funcionalidades planejadas:

- [ ] Persistência completa de vendas e compras no banco de dados
- [ ] Cálculo dinâmico no painel de Resumo financeiro
- [ ] RBAC — controle de acesso por perfil (admin, operador)
- [ ] Deploy via Railway ou InfinityFree

## 👤 Autor

**almeidaadev**

[![GitHub](https://img.shields.io/badge/GitHub-almeidaadev-181717?style=flat&logo=github)](https://github.com/almeidaadev)

---

> Projeto desenvolvido para fins de aprendizado e portfólio, aplicando PHP orientado a objetos, padrão Singleton, roteamento via query string e integração com MySQL.