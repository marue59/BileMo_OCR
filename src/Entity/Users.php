<?php

namespace App\Entity;


use App\Entity\Products;
use App\Entity\Customers;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @Hateoas\Relation(
 *          "show",
 *          href = @Hateoas\Route(
 *          "api_user_show",
 *          parameters = {"id" = "expr(object.getId())" },
 *          absolute = true,
 * ))
 * @Hateoas\Relation(
 *          "self",
 *          href = @Hateoas\Route(
 *          "api_user_show_id",
 *          parameters = {"user_id" = "expr(object.getId())" },
 *          absolute = true,
 * ))
 * 
 * @Hateoas\Relation(
 *          "customers",
 *          embedded = @Hateoas\Embedded("expr(object.getCustomers())"
 * ))
 * 
 * @Hateoas\Relation(
 *          "create",
 *          href = @Hateoas\Route(
 *          "api_user_create",
 *          absolute = true
 * ))
 * 
 * @Hateoas\Relation( 
 *          "delete", 
*           href = @Hateoas\Route( 
 *          "delete", 
 *          parameters = { "id" = "expr(object.getId())" }, 
 *          absolute = true         
 * )) 
 */
class Users
{
    /**
     * @Serializer\Groups({"listUser", "create"})
     * @Serializer\Since("1.0")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Groups({"listUser", "create"})
     * @Serializer\Since("1.0")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3, max = 25
     * )
     */
    private $name;

    /**
     * @Serializer\Groups({"listUser", "create"})
     * @Serializer\Since("1.0")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @Serializer\Groups({"listUser"})
     * @Serializer\Since("1.0")
     * @ORM\ManyToOne(targetEntity=Customers::class, inversedBy="users")
     */
    private $customers;

    /**
     * @ORM\ManyToMany(targetEntity=Products::class, mappedBy="users")
     * @Serializer\Since("1.0")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCustomers(): ?Customers
    {
        return $this->customers;
    }

    public function setCustomers(?Customers $customers): self
    {
        $this->customers = $customers;

        return $this;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addUser($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeUser($this);
        }

        return $this;
    }
}
