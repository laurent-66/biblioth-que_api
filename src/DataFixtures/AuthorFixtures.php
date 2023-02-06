<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorFixtures extends Fixture
{
    public const AUTH_REF = 'auth-ref_%s';

    private $slugger;
    
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $author = new Author();
            $author->setFirstName($faker->firstName());
            $author->setLastName($faker->lastName());
            $author->setCreatedAt(new \Datetime);
            $author->setUpdatedAt(new \Datetime);
            $manager->persist($author);
            $this->addReference(sprintf(self::AUTH_REF, $i), $author);
        }

        $manager->flush();
    }
}