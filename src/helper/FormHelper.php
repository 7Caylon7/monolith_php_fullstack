<?php
namespace App\helper;

class FormHelper
{
    public static function gerarToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public static function validarCampos(array $dados, array $obrigatorios): array
    {
        $faltando = [];
        foreach ($obrigatorios as $campo) {
            if (empty(trim($dados[$campo] ?? ''))) {
                $faltando[] = $campo;
            }
        }
        return $faltando;
    }

    public static function limparDados(array $dados): array
    {
        return array_map('trim', $dados);
    }

    public static function sanitizar(array $dados): array
    {
        $sanitizados = [];
        foreach ($dados as $chave => $valor) {
            $sanitizados[$chave] = htmlspecialchars(trim($valor ?? ''));
        }
        return $sanitizados;
    }
}