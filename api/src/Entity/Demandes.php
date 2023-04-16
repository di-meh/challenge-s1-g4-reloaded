<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DemandesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DemandesRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['demandes:read']],
    denormalizationContext: ['groups' => ['demandes:write']],
)]
class Demandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['demandes:read'])]
    #[ORM\OneToOne(inversedBy: 'demandes')]
    private ?User $owner = null;

    #[Groups(['demandes:read', 'demandes:write'])]
    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[Groups(['demandes:read', 'demandes:write'])]
    #[ORM\Column(length: 300, nullable: true)]
    private ?string $message = null;

    #[Groups(['demandes:read', 'demandes:write'])]
    #[ORM\Column]
    private ?string $tel = null;

    #[Groups(['demandes:read', 'demandes:write'])]
    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

}
