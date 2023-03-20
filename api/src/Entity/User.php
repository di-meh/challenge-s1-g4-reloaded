<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
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

    #[Groups(['user:put:change_role'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups(['user:post', 'user:put'])]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[Groups(['user:post', 'user:put'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $verified = false;
    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Bid::class, orphanRemoval: true)]
    private Collection $bids;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
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

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setCreator($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getCreator() === $this) {
                $bid->setCreator(null);
            }
        }

        return $this;
    }
}
