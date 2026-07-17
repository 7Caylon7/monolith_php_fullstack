<?php
namespace App\controller;

use App\service\ProdutoService;
use App\helper\FormHelper;

class ProdutoController
{
    private ProdutoService $service;
    private array $dados = [];
    private string $mensagem = '';
    private bool $erro = false;

    public function __construct(ProdutoService $service)
    {
        $this->service = $service;
        
        // Inicializa sessão se não estiver ativa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Gera token
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = FormHelper::gerarToken();
        }
    }

    public function processarRequisicao(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processarPost();
        }
        
        $this->processarResultadoSessao();
    }

    private function processarPost(): void
    {
        // Verifica token
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            $_SESSION['mensagem'] = 'Token inválido!';
            $_SESSION['erro'] = true;
            $this->redirect();
            return;
        }

        // Processa formulário
        $resultado = $this->service->cadastrar($_POST);
        $_SESSION['resultado'] = $resultado;
        
        // Gera novo token
        $_SESSION['token'] = FormHelper::gerarToken();
        
        // Redireciona (PRG)
        $this->redirect();
    }

    private function processarResultadoSessao(): void
    {
        if (isset($_SESSION['resultado'])) {
            $resultado = $_SESSION['resultado'];
            unset($_SESSION['resultado']);
            
            if ($resultado['sucesso']) {
                $this->mensagem = $resultado['mensagem'];
                $this->erro = false;
                $this->dados = [];
            } else {
                $this->mensagem = $resultado['erro'];
                $this->erro = true;
                $this->dados = $resultado['dados'] ?? [];
            }
        }

        // Recupera mensagens de erro
        if (isset($_SESSION['mensagem'])) {
            $this->mensagem = $_SESSION['mensagem'];
            unset($_SESSION['mensagem']);
        }
        if (isset($_SESSION['erro'])) {
            $this->erro = $_SESSION['erro'];
            unset($_SESSION['erro']);
        }
    }

    private function redirect(): void
    {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Getters para a view
    public function getDados(): array
    {
        return $this->dados;
    }

    public function getMensagem(): string
    {
        return $this->mensagem;
    }

    public function getErro(): bool
    {
        return $this->erro;
    }

    public function getToken(): string
    {
        return $_SESSION['token'] ?? FormHelper::gerarToken();
    }

    public function getProdutos(): array
    {
        return $this->service->listarTodos();
    }
}