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
    }
?>