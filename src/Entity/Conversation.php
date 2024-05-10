<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'conversation_user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'participant_user')]
    private Collection $participant;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'conversation')]
    private Collection $message_conversation;

    public function __construct()
    {
        $this->participant = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->message_conversation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participant->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessageConversation(): Collection
    {
        return $this->message_conversation;
    }

    public function addMessageConversation(Message $messageConversation): static
    {
        if (!$this->message_conversation->contains($messageConversation)) {
            $this->message_conversation->add($messageConversation);
            $messageConversation->setConversation($this);
        }

        return $this;
    }

    public function removeMessageConversation(Message $messageConversation): static
    {
        if ($this->message_conversation->removeElement($messageConversation)) {
            // set the owning side to null (unless already changed)
            if ($messageConversation->getConversation() === $this) {
                $messageConversation->setConversation(null);
            }
        }

        return $this;
    }
}
