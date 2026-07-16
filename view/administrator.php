<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\config\Database;
use App\repository\ProdutoRepository;

session_start();

$dadosPreenchidos = []; // ← DEFINIDO AQUI!
$mensagem = '';
$erro = false;

$conn = Database::getConnection();
$repo = new ProdutoRepository($conn);
$produtos = $repo->buscarProdutos();

// Gera token CSRF
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// PRG Pattern - Verifica se tem dados para processar (vindos da sessão)
if (isset($_SESSION['processar_post'])) {
    $dadosPost = $_SESSION['dados_post'] ?? [];
    unset($_SESSION['processar_post']);
    unset($_SESSION['dados_post']);
    
    if (!empty($dadosPost)) {
        $camposObrigatorios = ['codigo', 'nome', 'valor', 'quantidade'];
        $camposFaltando = [];

        // CORREÇÃO: foreach com $camposObrigatorios
        foreach ($camposObrigatorios as $campo) {
            if (!isset($dadosPost[$campo]) || empty(trim($dadosPost[$campo]))) {
                $camposFaltando[] = $campo;
            }
        }

        if (!empty($camposFaltando)) {
            $mensagem = "Campos obrigatórios faltando: " . implode(', ', $camposFaltando);
            $erro = true;
            $dadosPreenchidos = $dadosPost;
        } else {
            try {
                $dadosCadastro = [
                    'codigo' => trim($dadosPost['codigo']),
                    'nome' => trim($dadosPost['nome']),
                    'valor' => trim($dadosPost['valor']),
                    'quantidade' => trim($dadosPost['quantidade']),
                ];

                $resultado = $repo->cadastrarProduto($dadosCadastro);

                if ($resultado) {
                    $mensagem = "Produto cadastrado com sucesso!";
                    $erro = false;
                    $dadosPreenchidos = []; // Limpa o formulário
                    $_SESSION['token'] = bin2hex(random_bytes(32)); // Novo token
                    
                    // Atualiza lista de produtos
                    $produtos = $repo->buscarProdutos();
                } else {
                    $mensagem = "Erro ao cadastrar produto.";
                    $erro = true;
                    $dadosPreenchidos = $dadosCadastro;
                }
            } catch (\Exception $e) {
                $mensagem = "Erro no cadastro: " . $e->getMessage();
                $erro = true;
                $dadosPreenchidos = $dadosCadastro ?? [];
            }
        }
    }
}

// Processa o POST e redireciona (PRG Pattern)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica token CSRF
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        $_SESSION['mensagem'] = "Token inválido!";
        $_SESSION['erro'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Salva dados na sessão e redireciona
    $_SESSION['dados_post'] = $_POST;
    $_SESSION['processar_post'] = true;
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Recupera mensagens da sessão (para exibir após redirecionamento)
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}
if (isset($_SESSION['erro'])) {
    $erro = $_SESSION['erro'];
    unset($_SESSION['erro']);
}
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
            <div class="mensagem <?php echo $erro ? 'erro' : 'sucesso'; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            
            <!-- Código de Barras -->
            <div class="form-group">
                <label for="codigo">
                    Código de Barras <span class="campo-obrigatorio">*</span>
                </label>
                <input type="text"
                    id="codigo"
                    name="codigo"
                    value="<?php echo htmlspecialchars($dadosPreenchidos['codigo'] ?? ''); ?>"
                    required>
            </div>

            <!-- Nome -->
            <div class="form-group">
                <label for="nome">
                    Nome do Produto <span class="campo-obrigatorio">*</span>
                </label>
                <input type="text"
                    id="nome"
                    name="nome"
                    value="<?php echo htmlspecialchars($dadosPreenchidos['nome'] ?? ''); ?>"
                    required>
            </div>

            <!-- Preço -->
            <div class="form-group">
                <label for="valor">
                    Preço (R$) <span class="campo-obrigatorio">*</span>
                </label>
                <input type="number"
                    id="valor"
                    name="valor"
                    step="0.01"
                    min="0"
                    value="<?php echo htmlspecialchars($dadosPreenchidos['valor'] ?? ''); ?>"
                    required>
            </div>

            <!-- Quantidade -->
            <div class="form-group">
                <label for="quantidade">
                    Quantidade em Estoque <span class="campo-obrigatorio">*</span>
                </label>
                <input type="number"
                    id="quantidade"
                    name="quantidade"
                    min="0"
                    value="<?php echo htmlspecialchars($dadosPreenchidos['quantidade'] ?? ''); ?>"
                    required>
            </div>

            <button type="submit" class="btn">Cadastrar Produto</button>
            <a href="listar_produtos.php" style="margin-left: 10px;">Cancelar</a>
        </form>

        <?php if (!empty($produtos)): ?>
        <table>
            <caption>Produtos em Estoque</caption>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produtos as $produto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($produto['codigo_barra'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($produto['nome'] ?? ''); ?></td>
                    <td>R$ <?php echo number_format($produto['valor'] ?? 0, 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($produto['quantidade'] ?? 0); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>