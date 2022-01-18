<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Products;
use App\Entity\Customers;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
     /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
      
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $customers = new Customers();
            $customers->setFullname($faker->name());
            $customers->setPassword($this->encoder->hashPassword($customers, 'password'));
            $customers->setEmail("customers$i@email.com");

            $manager->persist($customers);
        }

        $users = $manager->getRepository(Users::class)->findAll();
        $colors = ["blue", "red", "gold"];

        for ($i = 0; $i < 15; $i++) {
            $products = new Products();
            $products->setBrand('marques'.$i);
            $products->setModel('models'.$i);
            $products->setColor($faker->randomElement($colors));
            $products->setCapacity($faker->numberBetween(1, 20));
            $products->setPrice($faker->numberBetween(50, 500));
            $products->setDescription($faker->text());
            $products->getUsers($faker->randomElement($users));

            $manager->persist($products);
        }

        $products = $manager->getRepository(Products::class)->findAll();
        $customers = $manager->getRepository(Customers::class)->findAll();


        for ($i = 0; $i < 15; $i++) {
            $users = new Users();
            $users->setName($faker->name());
            $users->getProducts($faker->randomElement($products));
            $users->getCustomers($faker->randomElement($customers));
            $users->setEmail("users$i@email.com");

            $manager->persist($users);
        }

        $manager->flush();
    }
}
