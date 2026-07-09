<?php
require_once './config/config.php';
require_once './src/repository/ProdutoRepository.php';
require_once './src/validator/produtoValidator.php';

$repo = new ProdutoRepository($conn);
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['codigo_produto'])) {
    
    $validator = new ProdutoValidator($repo);

    $resultado = $validator->validaTamanhoEntrada($_POST['codigo_produto']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Produtos</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
        <img class="col-md-1 mb-2 mb-md-0" src="./public/images/logo.png" alt="logo">

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li class="nav-link px-2 link-secondary">Home</li>
            <li class="nav-link px-2 text-light">Sobre</li>
            <li class="nav-link px-2 text-light">Contato</li>
        </ul>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li class="nav-link px-2 text-light">Facebook</li>
            <li class="nav-link px-2 text-light">Instagram</li>
            <li class="nav-link px-2 text-light">WhatsApp</li>
        </ul>
    </header>

    <main class="flex-grow-1 bg-body-tertiary ">
        <form class="container py-3 d-flex justify-content-center" method="POST" action="index.php">
            <h3 class="me-5">Consultar Produto:</h3>
            <input class="form-control w-25 me-1" type="text" placeholder="Código de Barras" name="codigo_produto">
            <button class="btn btn-warning" type="submit">Pesquisar</button>
        </form>
        <?php
        if (isset($resultado)) {
            if ($resultado['message']) {
                echo "❌ " . $resultado['message'];
            }
        }
        ?>
    </main>

    <footer class="d-flex flex-wrap align-items-center justify-content-between py-3 border-top bg-dark mt-auto px-4">
        <p class="text-light">Caylon Solon. Todos os direitos reservados</p>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li class="nav-link px-2 text-light">Facebook</li>
            <li class="nav-link px-2 text-light">Instagram</li>
            <li class="nav-link px-2 text-light">WhatsApp</li>
        </ul>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>