<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\config\Database;
use App\repository\ProdutoRepository;

$resultado = null;
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo']) && !empty($_POST['codigo'])) {
    try {
        $conn = Database::getConnection();
        $repo = new ProdutoRepository($conn);
        $resultado = $repo->buscarCodigoBarra($_POST['codigo']);

        if (empty($resultado)) {
            $mensagem = "Nenhum produto encontrado com o código: " . htmlspecialchars($_POST['codigo']);
        }
    } catch (\Exception $e) {
        $mensagem = "Erro: " . $e->getMessage();
    }
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

        <a href="view/administrator.php" class="nav-link px-2 text-light">Entrar</a>
    </header>

    <main class="flex-grow-1">
        <form class="container py-3 d-flex justify-content-center" method="POST" action="index.php">
            <label class="me-5">Consultar Produto:</label>
            <input class="form-control w-25 me-1" type="text" placeholder="Código de Barras" name="codigo" id="codigo" required>
            <button class="btn btn-warning" type="submit">Pesquisar</button>
        </form>

        <?php if ($resultado) { ?>
            <table class="container table table-striped table-dark rounded-3 overflow-hidden border-0 w-50">
                <thead class="rounded-3">
                    <tr>
                        <th scope="col" class="w-25">Produto</th>
                        <th scope="col" class="w-25">Valor</th>
                        <th scope="col" class="w-25">Quantidade</th>
                    </tr>
                </thead>
                <tr class="rounded-3">
                    <td scope="row" class="w-25"><?php echo htmlspecialchars($resultado['nome']) ?></td>
                    <td scope="row" class="w-25"><?php echo htmlspecialchars($resultado['valor']) ?></td>
                    <td scope="row" class="w-25"><?php echo htmlspecialchars($resultado['quantidade']) ?></td>
                </tr>
            </table>
        <?php } ?>

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