<?php

namespace App\Entity;

use App\Repository\SpecialRegimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialRegimeRepository::class)]
class SpecialRegime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'specialRegime_event')]
    #[ORM\JoinTable(name: 'specialRegime_event')]
    private Collection $event_specialRegime;

    public function __construct()
    {
        $this->event_specialRegime = new ArrayCollection();
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
    public function getEventSpecialRegime(): Collection
    {
        return $this->event_specialRegime;
    }

    public function addEventSpecialRegime(Event $eventSpecialRegime): static
    {
        if (!$this->event_specialRegime->contains($eventSpecialRegime)) {
            $this->event_specialRegime->add($eventSpecialRegime);
        }

        return $this;
    }

    public function removeEventSpecialRegime(Event $eventSpecialRegime): static
    {
        $this->event_specialRegime->removeElement($eventSpecialRegime);

        return $this;
    }
}
