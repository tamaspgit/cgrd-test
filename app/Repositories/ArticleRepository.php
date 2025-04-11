<?php

namespace CGRD\Repositories;

use CGRD\Database\DatabaseInterface;
use CGRD\Models\Article;

class ArticleRepository
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }   

    public function findById(int $id): ?Article
    {        
        $data = $this->db->query('SELECT * FROM articles WHERE id = :id', ['id' => $id]);        

        return $data ? $this->mapToArticle($data[0]) : null;
    }

    public function findAll(): array
    {
        return $this->db->query('SELECT * FROM articles ORDER BY created_at DESC');
    }

    public function findAllByAuthorId(int $id) 
    {
        return $this->db->query('SELECT * FROM articles WHERE author_id = :id ORDER BY created_at DESC', ['id' => $id]);
    }

    public function save(Article $article): bool
    {
        return $this->db->insert('articles', $article->toArray());
    }

    public function update(Article $article): bool
    {
        return $this->db->update('articles', $article->toArray(), ['id' => $article->getId()]);
    }

    public function delete(int $id): bool
    {
        return $this->db->delete('articles', ['id' => $id]);        
    }

    private function mapToArticle(array $data): Article
    {
        $article = new Article();
        $article->setId($data['id']);
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        $article->setCreatedAt($data['created_at']);
        $article->setAuthorId($data['author_id']);

        return $article;
    }
}
