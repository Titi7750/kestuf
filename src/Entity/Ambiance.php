<?php

namespace App\Entity;

use App\Repository\AmbianceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmbianceRepository::class)]
class Ambiance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'ambiance_event')]
    #[ORM\JoinTable(name: 'ambiance_event')]
    private Collection $event_ambiance;

    public function __construct()
    {
        $this->event_ambiance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventAmbiance(): Collection
    {
        return $this->event_ambiance;
    }

    public function addEventAmbiance(Event $eventAmbiance): static
    {
        if (!$this->event_ambiance->contains($eventAmbiance)) {
            $this->event_ambiance->add($eventAmbiance);
        }

        return $this;
    }

    public function removeEventAmbiance(Event $eventAmbiance): static
    {
        $this->event_ambiance->removeElement($eventAmbiance);

        return $this;
    }
}
