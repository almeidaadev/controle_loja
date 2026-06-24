<?php require "Parts/Header.php" ?>
<?php require "Parts/NavigationSide.php" ?>

<?php

$page = $_GET["page"] ?? "compras";
$path = __DIR__ . "/Pages/" . $page . ".php";

if (!file_exists($path)) return "Page don't exist";

if ($page) {
    switch ($page) {
        case 'compras':
            require "Pages/Compras.php";
            break;
        case 'fornecedores':
            require "Pages/Fornecedores.php";
            break;
        case 'vendas':
            require "Pages/Vendas.php";
            break;
        case 'resumo':
            require "Pages/Resumo.php";
            break;

        default:
            # code...
            break;
    }
}
// Name cannot be blank
?>

<?php require "Parts/Footer.php" ?>