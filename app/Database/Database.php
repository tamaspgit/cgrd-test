<?php

namespace CGRD\Database;

use PDO;
use PDOException;

class Database implements DatabaseInterface
{
    private static $instance = null;

    private PDO $pdo;

    private function __construct(array $config)
    {        
        try {            
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};port={$config['port']};";
            
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,                
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(array $config): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);        

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        
    public function insert(string $table, array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(array_values($data));
    }

    public function update(string $table, array $data, array $conditions): bool
    {
        $set = implode(", ", array_map(fn($key) => "{$key} = ?", array_keys($data)));
        $where = implode(" AND ", array_map(fn($key) => "{$key} = ?", array_keys($conditions)));
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";        
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(array_merge(array_values($data), array_values($conditions)));
    }

    public function delete(string $table, array $conditions): bool
    {
        $where = implode(" AND ", array_map(fn($key) => "{$key} = ?", array_keys($conditions)));
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute(array_values($conditions));
    }
    
}