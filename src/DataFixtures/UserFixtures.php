<?php

namespace App\DataFixtures;

use Datetime;
use Faker\Factory;
use App\Entity\User;
use App\DataFixtures\BookFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public const USER_REF = 'user-ref_%s';
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword('Password');
            $user->setCreatedAt(new \Datetime);
            $user->setUpdatedAt(new \Datetime);
            $user->addBook($this->getReference('book-ref_' . $i));
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookFixtures::class
        ];
    }
}
