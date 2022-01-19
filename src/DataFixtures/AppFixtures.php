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
      
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $customer = new Customers();
            $customer->setFullname($faker->name());
            $customer->setPassword($this->encoder->hashPassword($customer, 'password'));
            $customer->setEmail("customers$i@email.com");
            $customer->setRoles(['ROLE_USER']);

            $manager->persist($customer);
        }
        $manager->flush();

        $customers = $manager->getRepository(Customers::class)->findAll();

        for ($i = 0; $i < 15; $i++) {
            $customer = $faker->randomElement($customers);

            $user = new Users();
            $user->setName($faker->name());
            $user->setEmail("users$i@email.com");
            
            $customer->addUser($user);

            $manager->persist($user);
        }
        $manager->flush();

        $users = $manager->getRepository(Users::class)->findAll();
        $colors = ["blue", "red", "gold"];

        for ($i = 0; $i < 15; $i++) {
            $user = $faker->randomElement($users);

            $product = new Products();
            $product->setBrand('marques'.$i);
            $product->setModel('models'.$i);
            $product->setColor($faker->randomElement($colors));
            $product->setCapacity($faker->numberBetween(1, 20));
            $product->setPrice($faker->numberBetween(50, 500));
            $product->setDescription($faker->text());

            $user->addProduct($product);

            $manager->persist($product);
        }
        $manager->flush();
       
    }
}
