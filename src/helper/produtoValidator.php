<?php
namespace ProdutoValidador;

use App\config\Database;
use App\repository\ProdutoRepository;


class FormHelper {
    public static function gerarToken(): string {
        return bin2hex(random_bytes(32));
    }

    public static function validarCampos(array $dados, array $obrigatorios): array {
        $faltando = [];
        foreach ($obrigatorios as $campo) {
            if (empty(trim($dados[$campo] ?? ''))) {
                $faltando[] = $campo;
            }
        }
        return $faltando;
    }

    public static function limparDados(array $dados): array {
        return array_map('trim', $dados);
    }

    public static function sanitizar(array $dados): array {
        $sanitizados = [];
        foreach ($dados as $chave => $valor) {
            $sanitizados[$chave] = htmlspecialchars(trim($valor ?? ''));
        }
        return $sanitizados;
    }
}


// class ProdutoValidator {

//     private const ERRO_TAMANHO = "O código do produto deve ter no mínimo 13 caracteres";
//     private const ERRO_VAZIO = "O código do produto não pode estar vazio";
//     private const ERRO_PRODUTO_NAO_ENCONTRADO = "Produto não encontrado";

//     private $conn;
    
//     public function __construct($conn) {
//         $this->conn = $conn;
//     }
    
//     private function getProdutoRepository() {
//         return new ProdutoRepository($this->conn);
//     }

//     public function validaTamanhoEntrada($codigo): array {
//         if(empty($codigo)){
//             return [
//                 'valid' => false,
//                 'message' => self::ERRO_VAZIO
//             ];
//         }

//         if(strlen($codigo) < 13){
//             return [
//                 'valid' => false,
//                 'message' => self::ERRO_TAMANHO
//             ];
//         }
//     }

// }
?>