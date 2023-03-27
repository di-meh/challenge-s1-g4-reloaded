<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['items:read']],
    denormalizationContext: ['groups' => ['items:write']],
)]


class Annonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['items:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['items:read', 'items:write'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['items:read', 'items:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['items:read', 'items:write'])]
    private ?Float $price = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[Groups(['items:read','items:write'])]
    private ?User $annonceOwner = null;

    #[Groups(['items:read','items:write'])]
    #[ORM\OneToMany(mappedBy: 'annonces', targetEntity: MediaObject::class, cascade: ['persist'])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAnnonceOwner(): ?User
    {
        return $this->annonceOwner;
    }

    public function setAnnonceOwner(?User $annonceOwner): self
    {
        $this->annonceOwner = $annonceOwner;

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(MediaObject $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnnonces($this);
        }

        return $this;
    }

    public function removeImage(MediaObject $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnnonces() === $this) {
                $image->setAnnonces(null);
            }
        }

        return $this;
    }
}
