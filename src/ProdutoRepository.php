<?php
    class ProdutoRepository
    {
        private $conn;

        public function __construct($connection)
        {
            $this->conn = $connection;
        }

        //Método para buscar produto por código de barra
        public function buscarCodigoBarra($codigo){
            try{
                $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE codigo_barra = ?");
                $stmt->execute([$codigo]);

                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                return $produto ? $produto : null;
            } catch(PDOException $e) {
                echo "Erro ao buscar código: " . $e->getMessage();
                return null;
            }
        }

        //Método para cadastrar produto por código de barra
        public function cadastrarProduto($dados) {
            try{
                $stmt = $this->conn->prepare("INSERT INTO produtos (codigo_barra, nome, valor, quantidade)
                VALUES (:codigo, :nome, :valor, :quantidade)");

                $stmt->execute([
                    ':codigo' => $dados['codigo'],
                    ':nome' => $dados['nome'],
                    ':valor' => $dados['valor'],
                    ':quantidade' => $dados['quantidade']
                ]);

                return ['sucess' => true, 'id' => $this->conn->lastInsertId()];
            } catch (PDOException $e) {
                return ['sucess' => false, 'erro' => $e->getMessage()];
            }
        }
        
    }
?>