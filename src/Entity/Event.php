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

    #[ORM\ManyToMany(targetEntity: Ambiance::class, mappedBy: 'event_ambiance')]
    private Collection $ambiance_event;

    #[ORM\ManyToMany(targetEntity: SpecialRegime::class, mappedBy: 'event_specialRegime')]
    private Collection $specialRegime_event;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    public function __construct()
    {
        $this->user_event_favorite = new ArrayCollection();
        $this->ambiance_event = new ArrayCollection();
        $this->specialRegime_event = new ArrayCollection();
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

    public function getOpenhours(): ?\DateTimeInterface
    {
        return $this->open_hours;
    }

    public function setOpenhours(?\DateTimeInterface $open_hours): static
    {
        $this->open_hours = $open_hours;

        return $this;
    }

    public function getClosehours(): ?\DateTimeInterface
    {
        return $this->close_hours;
    }

    public function setClosehours(?\DateTimeInterface $close_hours): static
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

    /**
     * @return Collection<int, Ambiance>
     */
    public function getAmbianceEvent(): Collection
    {
        return $this->ambiance_event;
    }

    public function addAmbianceEvent(Ambiance $ambianceEvent): static
    {
        if (!$this->ambiance_event->contains($ambianceEvent)) {
            $this->ambiance_event->add($ambianceEvent);
            $ambianceEvent->addEventAmbiance($this);
        }

        return $this;
    }

    public function removeAmbianceEvent(Ambiance $ambianceEvent): static
    {
        if ($this->ambiance_event->removeElement($ambianceEvent)) {
            $ambianceEvent->removeEventAmbiance($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SpecialRegime>
     */
    public function getSpecialRegimeEvent(): Collection
    {
        return $this->specialRegime_event;
    }

    public function addSpecialRegimeEvent(SpecialRegime $specialRegimeEvent): static
    {
        if (!$this->specialRegime_event->contains($specialRegimeEvent)) {
            $this->specialRegime_event->add($specialRegimeEvent);
            $specialRegimeEvent->addEventSpecialRegime($this);
        }

        return $this;
    }

    public function removeSpecialRegimeEvent(SpecialRegime $specialRegimeEvent): static
    {
        if ($this->specialRegime_event->removeElement($specialRegimeEvent)) {
            $specialRegimeEvent->removeEventSpecialRegime($this);
        }

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
