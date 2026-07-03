<?php
    require_once './config/config.php';
    require_once './src/ProdutoRepository.php';

    $repo = new ProdutoRepository($conn);

    $produto = $repo->buscarCodigoBarra('14253698032516');

    print_r($produto);

?>