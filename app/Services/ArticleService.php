<?php 

namespace CGRD\Services;

use CGRD\Core\Validator\Validator;
use CGRD\Models\Article;
use CGRD\Repositories\ArticleRepository;

class ArticleService {

    private array $errors;

    public function __construct(
        private ArticleRepository $articleRepository,
        private Article $article,
        private Validator $validator
    )
    {}

    public function findById(int $id): ?Article
    {
        return $this->articleRepository->findById($id);
    }

    public function findAll(): array
    {
        return $this->articleRepository->findAll();
    }
    
    public function findAllByAuthorId(int $id): array
    {
        return $this->articleRepository->findAllByAuthorId($id);
    }

    public function load(array|Article $data): ?Article
    {
        if (is_array($data)) {
            if (isset($data['id'])) {
                $this->article = $this->articleRepository->findById($data['id']);

                $this->article->setTitle($data['title']);
                $this->article->setContent($data['content']);
                                
                return $this->article;
            }            

            $this->article->setId(null);
            $this->article->setTitle($data['title']);
            $this->article->setContent($data['content']);
            $this->article->setAuthorId($data['authorId']);
            $this->article->setCreatedAt(null);

            return $this->article;
        }

        if ($data instanceof Article) {
            return $this->article = $data;
        }

        return null;
    }

    public function validate(array $data): bool
    {
        $this->errors = $this->validator::validate($this->article, $data);

        return empty($this->errors);
    }

    public function save(): bool
    {        
        return $this->articleRepository->save($this->article);
    }

    public function update(): bool
    {               
        return $this->articleRepository->update($this->article);
    }

    public function delete(int $id): bool
    {
        return $this->articleRepository->delete($id);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}