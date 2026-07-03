<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'monolito_php';

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        //Configura o modo de erro da conexão para que o eroo seja lançado como exeção
        //estas podem ser capturadas pelo catch
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
        echo "Conexão falhou: " . $e->getMessage();
    }

?>

