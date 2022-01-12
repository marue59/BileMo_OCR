<?php

namespace App\DataFixtures;

use Faker\Factory;
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
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $customers = new Customers();
            $customers->setFullname($faker->name());
            $customers->setPassword($this->encoder->hashPassword($customers, 'password'));
            $customers->setEmail("user$i@email.com");

            $manager->persist($customers);
        }
    
        $manager->flush();
    }
}
