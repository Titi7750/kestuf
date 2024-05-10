<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $send_at = null;

    #[ORM\Column]
    private ?bool $isRead = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $read_at = null;

    #[ORM\ManyToOne(inversedBy: 'message_user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sendBy = null;

    #[ORM\ManyToOne(inversedBy: 'message_conversation')]
    private ?Conversation $conversation = null;

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

    public function getSendAt(): ?\DateTimeImmutable
    {
        return $this->send_at;
    }

    public function setSendAt(\DateTimeImmutable $send_at): static
    {
        $this->send_at = $send_at;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getReatAt(): ?\DateTimeImmutable
    {
        return $this->read_at;
    }

    public function setReatAt(?\DateTimeImmutable $read_at): static
    {
        $this->read_at = $read_at;

        return $this;
    }

    public function getSendBy(): ?User
    {
        return $this->sendBy;
    }

    public function setSendBy(?User $sendBy): static
    {
        $this->sendBy = $sendBy;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }
}
