<?php

namespace App\Entity;

use App\Repository\CommentEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentEventRepository::class)]
class CommentEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $active = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?bool $rgpd = null;

    #[ORM\ManyToOne(inversedBy: 'commentEvent_event')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event_commentEvent = null;

    #[ORM\ManyToOne(inversedBy: 'commentEvent_user_id')]
    private ?User $user_commentEvent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isRgpd(): ?bool
    {
        return $this->rgpd;
    }

    public function setRgpd(bool $rgpd): static
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    public function getEventCommentEvent(): ?Event
    {
        return $this->event_commentEvent;
    }

    public function setEventCommentEvent(?Event $event_commentEvent): static
    {
        $this->event_commentEvent = $event_commentEvent;

        return $this;
    }

    public function getUserCommentEvent(): ?User
    {
        return $this->user_commentEvent;
    }

    public function setUserCommentEvent(?User $user_commentEvent): static
    {
        $this->user_commentEvent = $user_commentEvent;

        return $this;
    }
}
