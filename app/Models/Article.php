<?php

namespace CGRD\Models;

use CGRD\Core\Validator\ValidatableInterface;

class Article implements ValidatableInterface
{
    private ?int $id;

    private string $title;

    private string $content;

    private int $authorId;

    private ?\DateTime $createdAt;    

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => $this->authorId,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function getValidationRules(): array
    {
        return [
            'title' => 'required|min:3',
            'content' => 'required|min:3',
            'authorId' => 'required|integer',            
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
