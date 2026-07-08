<?php
    require_once './config/config.php';
    require_once './src/ProdutoRepository.php';

    $repo = new ProdutoRepository($conn);

    $produto = $repo->buscarCodigoBarra('14253698032516');

    echo "<pre>";
    print_r($produto);
    echo "</pre>";

    $dados = [
    'codigo' => '7891234567890',
    'nome' => 'Produto Teste',
    'valor' => 99.90,
    'quantidade' => 10
];

// Executa a função
$resultado = $repo->cadastrarProduto($dados);

// Exibe o resultado
echo "<pre>";
print_r($resultado);
echo "</pre>";


$deletarproduto = $repo->deletarProduto('7891234567890');
echo "<pre>";
print_r($deletarproduto);
echo "</pre>";

?>