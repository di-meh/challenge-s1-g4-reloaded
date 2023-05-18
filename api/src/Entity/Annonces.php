<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put; 
use ApiPlatform\Metadata\GetCollection;


#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['items:read']],
    denormalizationContext: ['groups' => ['items:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: "is_granted('ROLE_VENDEUR')",
            securityMessage: 'Only sellers can create articles'
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or object.annonceOwner == user",
            securityMessage: 'Only admins and the current user can update their own articles'
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.annonceOwner == user",
            securityMessage: 'Only admins and the current user can delete their own articles'
        )
    ]
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

    #[ORM\Column(length: 1020)]
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

    #[Groups(['items:read','items:write'])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?string $stripe_price_id = null;

    #[Groups(['items:read','items:write'])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?string $stripe_product_id = null;

    // #[Groups(['items:read','items:write'])]
    #[ORM\ManyToOne(inversedBy: 'bought')]
    private ?User $buyer = null;

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

    public function getStripePriceId(): ?string
    {
        return $this->stripe_price_id;
    }

    public function setStripePriceId(string $stripe_price_id): self
    {
        $this->stripe_price_id = $stripe_price_id;

        return $this;
    }

    public function getStripeProductId(): ?string
    {
        return $this->stripe_product_id;
    }

    public function setStripeProductId(string $stripe_product_id): self
    {
        $this->stripe_product_id = $stripe_product_id;

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }
}
