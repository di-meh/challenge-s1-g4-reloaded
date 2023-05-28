<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use App\Controller\VerifyEmailController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins and the current user can get their own user'
        ),
        new Post(
            denormalizationContext: ['groups' => ['user:post']],
            security: 'is_granted("ROLE_ADMIN") or is_granted("IS_AUTHENTICATED_FULLY") == false',
            securityMessage: 'Only admins and not logged in users can create users'
        ),
        new Put(
            denormalizationContext: ['groups' => ['user:put']],
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins and the current user can update their own user'
        ),
        new Put(
            uriTemplate: '/users/{id}/change_role',
            denormalizationContext: ['groups' => ['user:put:change_role']],
            securityPostDenormalize: "is_granted('ROLE_ADMIN')",

        ),
        new Patch(
            uriTemplate: '/users/{id}/update_vendeur',
            denormalizationContext: ['groups' => ['user:patch:update_vendeur']],
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Patch(
            uriTemplate: '/users/{id}/update_annonceur',
            denormalizationContext: ['groups' => ['user:patch:update_annonceur']],
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins and the current user can delete their own user'
        )
    ],
//    normalizationContext: ['groups' => ['user:read']],
//    denormalizationContext: ['groups' => ['user:write']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['user:read','user:post', 'user:put', 'items:read', 'items:write', 'demandes:read'])]
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    private ?string $email = null;

    #[Groups(['user:read','user:put:change_role', 'user:patch:update_vendeur', 'user:patch:update_annonceur'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups(['user:post', 'user:put'])]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[Groups(['user:read','user:post', 'user:put', 'items:read','items:write', 'demandes:read'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $verified = false;

    #[Groups(['user:put'])]
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Demandes::class)]
    private Collection $demandes;

    #[Groups(['user:put', 'user:patch:update_vendeur', 'user:patch:update_annonceur'])]
    #[ORM\Column(nullable: true)]
    private ?string $tel = null;

    #[Groups(['user:put', 'user:patch:update_vendeur', 'user:patch:update_annonceur'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[Groups(['user:put', 'user:patch:update_annonceur'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $entrepriseName = null;

    #[Groups(['user:put', 'user:patch:update_annonceur'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $entrepriseLink = null;

    #[ORM\OneToMany(mappedBy: 'annonceOwner', targetEntity: Annonces::class)]
    private Collection $annonces;

//    #[Groups(['user:write'])]
    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: Annonces::class)]
    private Collection $bought;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->bought = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemandes(Demandes $demandes): self
    {
        if (!$this->demandes->contains($demandes)) {
            $this->demandes->add($demandes);
            $demandes->setOwner($this);
        }

        return $this;
    }
    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEntrepriseName(): ?string
    {
        return $this->entrepriseName;
    }

    public function setEntrepriseName(?string $entrepriseName): self
    {
        $this->entrepriseName = $entrepriseName;

        return $this;
    }

    public function getEntrepriseLink(): ?string
    {
        return $this->entrepriseLink;
    }

    public function setEntrepriseLink(?string $entrepriseLink): self
    {
        $this->entrepriseLink = $entrepriseLink;

        return $this;
    }

    /**
     * @return Collection<int, Annonces>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setAnnonceOwner($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getAnnonceOwner() === $this) {
                $annonce->setAnnonceOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Annonces>
     */
    public function getBought(): Collection
    {
        return $this->bought;
    }

    public function addBought(Annonces $bought): self
    {
        if (!$this->bought->contains($bought)) {
            $this->bought->add($bought);
            $bought->setBuyer($this);
        }

        return $this;
    }

    public function removeBought(Annonces $bought): self
    {
        if ($this->bought->removeElement($bought)) {
            // set the owning side to null (unless already changed)
            if ($bought->getBuyer() === $this) {
                $bought->setBuyer(null);
            }
        }

        return $this;
    }
}
