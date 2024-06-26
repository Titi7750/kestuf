<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_SURNAME', fields: ['surname'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['surname'], message: 'There is already an account with this surname')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'user_event_favorite')]
    private Collection $event_user_favorite;

    #[ORM\OneToMany(targetEntity: CommentEvent::class, mappedBy: 'user_commentEvent')]
    private Collection $commentEvent_user;

    #[ORM\OneToMany(targetEntity: CommentUser::class, mappedBy: 'user_send_comment')]
    private Collection $user_send_comment;

    #[ORM\OneToMany(targetEntity: CommentUser::class, mappedBy: 'user_receive_comment')]
    private Collection $user_receive_comment;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'user_event_participant')]
    private Collection $event_user_participant;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'sendBy')]
    private Collection $message_user;

    #[ORM\OneToMany(targetEntity: Conversation::class, mappedBy: 'createdBy')]
    private Collection $conversation_user;

    #[ORM\ManyToMany(targetEntity: Conversation::class, mappedBy: 'participant')]
    private Collection $participant_user;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'user_event_outlet')]
    #[ORM\JoinTable(name: 'user_event_outlet')]
    private Collection $event_user_outlet;

    public function __construct()
    {
        $this->event_user_favorite = new ArrayCollection();
        $this->commentEvent_user = new ArrayCollection();
        $this->user_send_comment = new ArrayCollection();
        $this->user_receive_comment = new ArrayCollection();
        $this->event_user_participant = new ArrayCollection();
        $this->message_user = new ArrayCollection();
        $this->conversation_user = new ArrayCollection();
        $this->participant_user = new ArrayCollection();
        $this->event_user_outlet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventUserFavorite(): Collection
    {
        return $this->event_user_favorite;
    }

    public function addEventUserFavorite(Event $eventUserFavorite): static
    {
        if (!$this->event_user_favorite->contains($eventUserFavorite)) {
            $this->event_user_favorite->add($eventUserFavorite);
            $eventUserFavorite->addUserEventFavorite($this);
        }

        return $this;
    }

    public function removeEventUserFavorite(Event $eventUserFavorite): static
    {
        if ($this->event_user_favorite->removeElement($eventUserFavorite)) {
            $eventUserFavorite->removeUserEventFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentEvent>
     */
    public function getCommentEventUser(): Collection
    {
        return $this->commentEvent_user;
    }

    public function addCommentEventUser(CommentEvent $commentEventUser): static
    {
        if (!$this->commentEvent_user->contains($commentEventUser)) {
            $this->commentEvent_user->add($commentEventUser);
            $commentEventUser->setUserCommentEvent($this);
        }

        return $this;
    }

    public function removeCommentEventUser(CommentEvent $commentEventUser): static
    {
        if ($this->commentEvent_user->removeElement($commentEventUser)) {
            // set the owning side to null (unless already changed)
            if ($commentEventUser->getUserCommentEvent() === $this) {
                $commentEventUser->setUserCommentEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentUser>
     */
    public function getUserSendComment(): Collection
    {
        return $this->user_send_comment;
    }

    public function addUserSendComment(CommentUser $userSendComment): static
    {
        if (!$this->user_send_comment->contains($userSendComment)) {
            $this->user_send_comment->add($userSendComment);
            $userSendComment->setUserSendComment($this);
        }

        return $this;
    }

    public function removeUserSendComment(CommentUser $userSendComment): static
    {
        if ($this->user_send_comment->removeElement($userSendComment)) {
            // set the owning side to null (unless already changed)
            if ($userSendComment->getUserSendComment() === $this) {
                $userSendComment->setUserSendComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentUser>
     */
    public function getUserReceiveComment(): Collection
    {
        return $this->user_receive_comment;
    }

    public function addUserReceiveComment(CommentUser $userReceiveComment): static
    {
        if (!$this->user_receive_comment->contains($userReceiveComment)) {
            $this->user_receive_comment->add($userReceiveComment);
            $userReceiveComment->setUserReceiveComment($this);
        }

        return $this;
    }

    public function removeUserReceiveComment(CommentUser $userReceiveComment): static
    {
        if ($this->user_receive_comment->removeElement($userReceiveComment)) {
            // set the owning side to null (unless already changed)
            if ($userReceiveComment->getUserReceiveComment() === $this) {
                $userReceiveComment->setUserReceiveComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventUserParticipant(): Collection
    {
        return $this->event_user_participant;
    }

    public function addEventUserParticipant(Event $eventUserParticipant): static
    {
        if (!$this->event_user_participant->contains($eventUserParticipant)) {
            $this->event_user_participant->add($eventUserParticipant);
            $eventUserParticipant->addUserEventParticipant($this);
        }

        return $this;
    }

    public function removeEventUserParticipant(Event $eventUserParticipant): static
    {
        if ($this->event_user_participant->removeElement($eventUserParticipant)) {
            $eventUserParticipant->removeUserEventParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessageUser(): Collection
    {
        return $this->message_user;
    }

    public function addMessageUser(Message $messageUser): static
    {
        if (!$this->message_user->contains($messageUser)) {
            $this->message_user->add($messageUser);
            $messageUser->setSendBy($this);
        }

        return $this;
    }

    public function removeMessageUser(Message $messageUser): static
    {
        if ($this->message_user->removeElement($messageUser)) {
            // set the owning side to null (unless already changed)
            if ($messageUser->getSendBy() === $this) {
                $messageUser->setSendBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversationUser(): Collection
    {
        return $this->conversation_user;
    }

    public function addConversationUser(Conversation $conversationUser): static
    {
        if (!$this->conversation_user->contains($conversationUser)) {
            $this->conversation_user->add($conversationUser);
            $conversationUser->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConversationUser(Conversation $conversationUser): static
    {
        if ($this->conversation_user->removeElement($conversationUser)) {
            // set the owning side to null (unless already changed)
            if ($conversationUser->getCreatedBy() === $this) {
                $conversationUser->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getParticipantUser(): Collection
    {
        return $this->participant_user;
    }

    public function addParticipantUser(Conversation $participantUser): static
    {
        if (!$this->participant_user->contains($participantUser)) {
            $this->participant_user->add($participantUser);
            $participantUser->addParticipant($this);
        }

        return $this;
    }

    public function removeParticipantUser(Conversation $participantUser): static
    {
        if ($this->participant_user->removeElement($participantUser)) {
            $participantUser->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventUserOutlet(): Collection
    {
        return $this->event_user_outlet;
    }

    public function addEventUserOutlet(Event $eventUserOutlet): static
    {
        if (!$this->event_user_outlet->contains($eventUserOutlet)) {
            $this->event_user_outlet->add($eventUserOutlet);
        }

        return $this;
    }

    public function removeEventUserOutlet(Event $eventUserOutlet): static
    {
        $this->event_user_outlet->removeElement($eventUserOutlet);

        return $this;
    }
}
