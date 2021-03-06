<?php

namespace App\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use App\Entity\Users;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 * 
 * @Hateoas\Relation(
 *          "self",
 *          href = @Hateoas\Route(
 *          "product_detail",
 *          parameters = {"id" = "expr(object.getId())" },
 *          absolute = true
 * ))
 *
 */
class Products
{
    /**
     * @Serializer\Groups({"listProduct"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Since("1.0")
     */
    private $id;

    /**
     * @Serializer\Groups({"listProduct"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     */
    private $brand;

    /** 
     * @Serializer\Groups({"listProduct"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     */
    private $model;

    /**
     * @Serializer\Groups({"listProduct"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     */
    private $color;

    /**
     * @Serializer\Groups({"listProduct"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     */
    private $capacity;

    /**
     * @Serializer\Groups({"listProduct"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     */
    private $price;

    /**
     * @Serializer\Groups({"listProduct"})
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     */
    private $description;

    /**
     * @Serializer\Groups({"listProduct"})
     * @ORM\ManyToMany(targetEntity=Users::class, inversedBy="products")
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    public function setCapacity(string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
