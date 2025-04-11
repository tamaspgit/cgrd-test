<?php

namespace CGRD\Database;

interface DatabaseInterface {

    public static function getInstance(array $config);

    public function query(string $sql, array $params = []): array;

    public function insert(string $table, array $data): bool;

    public function update(string $table, array $data, array $conditions): bool;

    public function delete(string $table, array $conditions): bool;    

}