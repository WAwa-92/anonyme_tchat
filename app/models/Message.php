<?php

declare(strict_types=1);

namespace App\Models;

class Message
{
    public function __construct(
        private int $id,
        private int $salonId,
        private string $content,
        private bool $isPinned,
        private string $createdAt
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSalonId(): int
    {
        return $this->salonId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isPinned(): bool
    {
        return $this->isPinned;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
