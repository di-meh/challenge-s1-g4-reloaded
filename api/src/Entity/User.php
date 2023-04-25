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
            denormalizationContext: ['groups' => ['user:patch']],
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins and the current user can update their own user'
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins and the current user can delete their own user'
        )
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['user:post', 'user:put'])]
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    private ?string $email = null;

    #[Groups(['user:put:change_role', 'user:post', 'user:put', 'user:patch'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups(['user:post', 'user:put'])]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[Groups(['user:post', 'user:put', 'demandes:read'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $verified = false;

    #[Groups(['user:post', 'user:put'])]
    #[ORM\OneToOne(mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?Demandes $demandes = null;

    #[Groups(['user:post', 'user:put', 'user:patch'])]
    #[ORM\Column(nullable: true)]
    private ?int $tel = null;

    #[Groups(['user:post', 'user:put', 'user:patch'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[Groups(['user:post', 'user:put', 'user:patch'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isAnnonceur = null;

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

    public function getDemandes(): ?Demandes
    {
        return $this->demandes;
    }

    public function setDemandes(?Demandes $demandes): self
    {
        // unset the owning side of the relation if necessary
        if ($demandes === null && $this->demandes !== null) {
            $this->demandes->setOwner(null);
        }

        // set the owning side of the relation if necessary
        if ($demandes !== null && $demandes->getOwner() !== $this) {
            $demandes->setOwner($this);
        }

        $this->demandes = $demandes;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
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

    public function isIsAnnonceur(): ?bool
    {
        return $this->isAnnonceur;
    }

    public function setIsAnnonceur(?bool $isAnnonceur): self
    {
        $this->isAnnonceur = $isAnnonceur;

        return $this;
    }

}
