<?php require "parts/header.php" ?>
<?php require "parts/navigationSide.php" ?>

<?php

$page = $_GET["page"] ?? "compras";

$path = __DIR__ . "/pages/" . $page . ".php";

if (!file_exists($path)) return "Page don't exist";

switch ($page) {
    case 'compras':
        require $path;
        break;
    case 'fornecedores':
        require $path;
        break;
    case 'vendas':
        require $path;
        break;
    case 'resumo':
        require $path;
        break;

    default:
        # code...
        break;
}
// Name cannot be blank
?>

<?php require "parts/footer.php" ?>