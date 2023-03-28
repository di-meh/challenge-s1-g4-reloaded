<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BidRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: BidRepository::class)]
#[
    ApiResource(
        normalizationContext: ['groups' => ['bid:read']],
        denormalizationContext: ['groups' => ['bid:write']],
        operations: [
            new GetCollection(),
            new Get(),
            new Put(
                denormalizationContext: ['groups' => ['bid:update']]
            ),
            new Post(),
            new Delete()
        ]
    )
]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'finished' => 'exact'])]

class Bid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bid:read', 'bid:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bid:read', 'bid:write', 'bid:update'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['bid:read', 'bid:write', 'bid:update'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['bid:read', 'bid:write', 'bid:update'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    #[Groups(['bid:read', 'bid:write'])]
    private ?float $startPrice = null;

    #[ORM\Column]
    #[Groups(['bid:read', 'bid:write', 'bid:update'])]
    private ?float $actualPrice = null;

    #[ORM\Column]
    #[Groups(['bid:read', 'bid:write', 'bid:update'])]
    private ?bool $finished = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bid:read', 'bid:write'])]
    private ?User $creator = null;

    #[Groups(['bid:read', 'bid:update'])]
    #[ORM\ManyToOne(inversedBy: 'bidsInProgress')]
    private ?User $owner = null;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStartPrice(): ?float
    {
        return $this->startPrice;
    }

    public function setStartPrice(float $startPrice): self
    {
        $this->startPrice = $startPrice;

        return $this;
    }

    public function getActualPrice(): ?float
    {
        return $this->actualPrice;
    }

    public function setActualPrice(float $actualPrice): self
    {
        $this->actualPrice = $actualPrice;

        return $this;
    }

    public function isFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
