<?php

namespace App\Entity;

use App\Repository\CommentUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentUserRepository::class)]
class CommentUser
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

    #[ORM\ManyToOne(inversedBy: 'user_send_comment')]
    private ?User $user_send_comment = null;

    #[ORM\ManyToOne(inversedBy: 'user_receive_comment')]
    private ?User $user_receive_comment = null;

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

    public function getUserSendComment(): ?User
    {
        return $this->user_send_comment;
    }

    public function setUserSendComment(?User $user_send_comment): static
    {
        $this->user_send_comment = $user_send_comment;

        return $this;
    }

    public function getUserReceiveComment(): ?User
    {
        return $this->user_receive_comment;
    }

    public function setUserReceiveComment(?User $user_receive_comment): static
    {
        $this->user_receive_comment = $user_receive_comment;

        return $this;
    }
}
