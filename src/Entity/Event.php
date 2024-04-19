<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 500)]
    private ?string $address = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $price = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $open_hours = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $close_hours = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Category $category = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $picture = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'event_user_favorite')]
    #[ORM\JoinTable(name: 'user_event_favorite')]
    private Collection $user_event_favorite;

    public function __construct()
    {
        $this->user_event_favorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOpen_hours(): ?\DateTimeInterface
    {
        return $this->open_hours;
    }

    public function setOpen_hours(?\DateTimeInterface $open_hours): static
    {
        $this->open_hours = $open_hours;

        return $this;
    }

    public function getClose_hours(): ?\DateTimeInterface
    {
        return $this->close_hours;
    }

    public function setClose_hours(?\DateTimeInterface $close_hours): static
    {
        $this->close_hours = $close_hours;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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

    /**
     * @return Collection<int, User>
     */
    public function getUserEventFavorite(): Collection
    {
        return $this->user_event_favorite;
    }

    public function addUserEventFavorite(User $userEventFavorite): static
    {
        if (!$this->user_event_favorite->contains($userEventFavorite)) {
            $this->user_event_favorite->add($userEventFavorite);
        }

        return $this;
    }

    public function removeUserEventFavorite(User $userEventFavorite): static
    {
        $this->user_event_favorite->removeElement($userEventFavorite);

        return $this;
    }

    public function isFavorite(User $user): bool
    {
        return $this->getUserEventFavorite()->contains($user);
    }
}
