<?php
namespace App\config;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $connection = null;
    
    public static function getConnection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }
        
        try {
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'monolito_php';
            
            self::$connection = new PDO(
                "mysql:host=$servername;dbname=$dbname",
                $username,
                $password
            );
            
            self::$connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            
            return self::$connection;
            
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Falha na conexão: " . $e->getMessage()
            );
        }
    }
}