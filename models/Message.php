<?php

namespace Models;

class Message
{
    private $id;
    private $salonId;
    private $content;
    private $isPinned;
    private $createdAt;

    public function __construct($id, $salonId, $content, $isPinned, $createdAt)
    {
        $this->id = $id;
        $this->salonId = $salonId;
        $this->content = $content;
        $this->isPinned = $isPinned;
        $this->createdAt = $createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSalonId()
    {
        return $this->salonId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function isPinned()
    {
        return $this->isPinned;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
