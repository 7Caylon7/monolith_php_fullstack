<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\config\Database;
use App\repository\ProdutoRepository;
use App\service\ProdutoService;
use App\controller\ProdutoController;


$conn = Database::getConnection();
$repository = new ProdutoRepository($conn);
$service = new ProdutoService($repository);
$controller = new ProdutoController($service);


$controller->processarRequisicao();


$dadosPreenchidos = $controller->getDados();
$mensagem = $controller->getMensagem();
$erro = $controller->getErro();
$token = $controller->getToken();
$produtos = $controller->getProdutos();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Produto</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?= $erro ? 'erro' : 'sucesso' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="token" value="<?= $token ?>">
            
            <div class="form-group">
                <label for="codigo">Código de Barras *</label>
                <input type="text" id="codigo" name="codigo" 
                       value="<?= htmlspecialchars($dadosPreenchidos['codigo'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="nome">Nome do Produto *</label>
                <input type="text" id="nome" name="nome" 
                       value="<?= htmlspecialchars($dadosPreenchidos['nome'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="valor">Preço (R$) *</label>
                <input type="number" id="valor" name="valor" step="0.01" min="0" 
                       value="<?= htmlspecialchars($dadosPreenchidos['valor'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade *</label>
                <input type="number" id="quantidade" name="quantidade" min="0" 
                       value="<?= htmlspecialchars($dadosPreenchidos['quantidade'] ?? '') ?>" required>
            </div>

            <button type="submit" class="btn">Cadastrar</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn-cancelar">Cancelar</a>
        </form>

        <h2>📋 Produtos em Estoque</h2>
        
        <?php if (!empty($produtos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['codigo_barra']) ?></td>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td>R$ <?= number_format($produto['valor'], 2, ',', '.') ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum produto cadastrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>