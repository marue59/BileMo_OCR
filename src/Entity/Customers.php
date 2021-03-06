<?php

namespace App\Entity;

use App\Entity\Users;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomersRepository::class)
 * @Hateoas\Relation(
 *     name = "api_user_show",
 *     embedded = @Hateoas\Embedded(
 *         "expr(object.getUsers())",
 *     )
 * )
 */
class Customers implements UserInterface, PasswordAuthenticatedUserInterface
{
    /** 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Since("1.0")
     */
    private $id;

    /**
     * @Serializer\Groups({"listUser"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min = 3, max = 25, minMessage="Le nom d'utilisateur doit avoir au moins 3 caractères",)
     * @Assert\Regex(
     *      "#^[a-zA-Z0-9._-]+$#", 
     *      message="Le nom d'utilisateur ne peut comporter que des caractères alphanumériques, points, tirets et underscores")
     * @Serializer\Since("1.0")
     */
    private $fullname;

    /**
     * 
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     */
    private $email;

    /**
     * 
     * @ORM\Column(type="json")
     * @Serializer\Since("1.0")
     */
    private $roles = [];

    /** 
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Serializer\Since("1.0")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="customers")
     * @Serializer\Since("1.0")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
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
     * The public representation of the user (e.g. a username, an email address, etc.)
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCustomers($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomers() === $this) {
                $user->setCustomers(null);
            }
        }

        return $this;
    }

    public function getUsername() 
    { 
      return $this->email; 
    }
}
