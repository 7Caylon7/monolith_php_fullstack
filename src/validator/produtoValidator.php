<?php
namespace ProdutoValidador;

use App\config\Database;
use App\repository\ProdutoRepository;


class ProdutoValidator {

    private const ERRO_TAMANHO = "O código do produto deve ter no mínimo 13 caracteres";
    private const ERRO_VAZIO = "O código do produto não pode estar vazio";
    private const ERRO_PRODUTO_NAO_ENCONTRADO = "Produto não encontrado";

    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    private function getProdutoRepository() {
        return new ProdutoRepository($this->conn);
    }

    public function validaTamanhoEntrada($codigo): array {
        if(empty($codigo)){
            return [
                'valid' => false,
                'message' => self::ERRO_VAZIO
            ];
        }

        if(strlen($codigo) < 13){
            return [
                'valid' => false,
                'message' => self::ERRO_TAMANHO
            ];
        }
    }

}
?>