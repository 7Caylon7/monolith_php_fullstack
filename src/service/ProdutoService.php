<?php
namespace App\service;

use App\repository\ProdutoRepository;
use App\helper\FormHelper;

class ProdutoService
{
    private ProdutoRepository $repository;

    public function __construct(ProdutoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function cadastrar(array $dados): array
    {
        $camposObrigatorios = ['codigo', 'nome', 'valor', 'quantidade'];
        
        // Valida campos
        $faltando = FormHelper::validarCampos($dados, $camposObrigatorios);
        if (!empty($faltando)) {
            return [
                'sucesso' => false,
                'erro' => "Campos obrigatórios: " . implode(', ', $faltando),
                'dados' => $dados
            ];
        }

        // Limpa dados
        $dadosLimpos = FormHelper::limparDados($dados);
        $dadosCadastro = [
            'codigo' => $dadosLimpos['codigo'],
            'nome' => $dadosLimpos['nome'],
            'valor' => $dadosLimpos['valor'],
            'quantidade' => $dadosLimpos['quantidade'],
        ];

        // Tenta cadastrar
        try {
            $resultado = $this->repository->cadastrarProduto($dadosCadastro);
            
            if ($resultado) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Produto cadastrado com sucesso!',
                    'dados' => []
                ];
            }
            
            return [
                'sucesso' => false,
                'erro' => 'Erro ao cadastrar produto.',
                'dados' => $dadosCadastro
            ];
            
        } catch (\Exception $e) {
            return [
                'sucesso' => false,
                'erro' => 'Erro no cadastro: ' . $e->getMessage(),
                'dados' => $dadosCadastro
            ];
        }
    }

    public function listarTodos(): array
    {
        return $this->repository->buscarProdutos();
    }
}