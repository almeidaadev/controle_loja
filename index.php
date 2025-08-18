<?php require "Parts/Header.php" ?>
<?php require "Parts/NavigationSide.php" ?>

<?php

if (isset($_GET["page"])) {
    $page = $_GET["page"];

    switch ($page) {
        case 'compras':
            include "Pages/Compras.php";
            break;
        case 'fornecedores':
            include "Pages/Fornecedores.php";
            break;
        case 'vendas':
            include "Pages/Vendas.php";
            break;
        case 'resumo':
            include "Pages/Resumo.php";
            break;

        default:
            # code...
            break;
    }
}
// Name cannot be blank
?>

<?php require "Parts/Footer.php" ?>