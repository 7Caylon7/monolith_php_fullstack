<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\config\Database;
use App\repository\ProdutoRepository;

$dadosCadastro = null;
$mensagem = '';
$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $camposObrigatorios = ['codigo', 'nome', 'valor', 'quantidade'];
    $camposFaltando = [];

    foreach ($camposFaltando as $campo) {
        if(!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            $camposFaltando[] = $campo;
        }
    }

    if (!empty($camposFaltando)) {
        $mensagem = "Campos obrigatórios faltando: " . implode(', ', $camposFaltando);
        $erro = true;
    } else {
        try {
            $dadosCadastro = [
                'codigo' => trim($_POST['codigo']),
                'nome' => trim($_POST['nome']),
                'valor' => trim($_POST['valor']),
                'quantidade' => trim($_POST['quantidade']),
            ];

            $conn = Database::getConnection();
            $repo = new ProdutoRepository($conn);

            $resultado = $repo->cadastrarProduto($dadosCadastro);

            if($resultado) {
                $mensagem = "Produto cadastrado com sucesso";
                $dadosCadastro = null;
            } else {
                $mensagem = "Erro ao cadastrar produto.";
                $erro = true;
            }
        } catch (\Exception $e) {
            $mensagem = "Erro no cadastro: " . $e->getMessage();
            $erro = true;
        }
    }
}

$dadosPreenchidos = $_POST ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                <input type="text" 
                       id="quantidade" 
                       name="quantidade" 
                       min="0" 
                       value="<?php echo htmlspecialchars($dadosPreenchidos['quantidade'] ?? ''); ?>" 
                       required>
            </div>
            
            <button type="submit" class="btn">Cadastrar Produto</button>
            <a href="listar_produtos.php" style="margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</body>
</html>