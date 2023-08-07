<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\DataFixtures\BookFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_REF = 'user-ref_%s';

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //default user credentials
            $user = new User();
            $user->setEmail('ibernier@yahoo.fr');
            $plaintextPassword = 'Password';
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setCreatedAt(new \Datetime);
            $user->setUpdatedAt(new \Datetime);
            $user->addBook($this->getReference('book-ref_1'));
            $manager->persist($user);



        for ($i = 1; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $plaintextPassword = 'Password';
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
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
