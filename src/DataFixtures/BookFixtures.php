<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\DataFixtures\AuthorFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookFixtures extends Fixture
{
    public const BOOK_REF = 'book-ref_%s';
    private $slugger;
    
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $booksByAuthor = 5;
        for ($i = 0; $i < 5; $i++) {
            self::collectionBooksByAuthor( $booksByAuthor , $manager, $i , $faker);
        }

        $manager->flush();
    }

    private int $booksByAuthor;
    private $manager;
    private int $i;
    private $faker;

    private function collectionBooksByAuthor($booksByAuthor, $manager , $i , $faker){

        for ($j = 0; $j < $booksByAuthor; $j++) {
            $book = new Book();
            $book->setIsbn( $faker->isbn13());
            $title = $faker->sentence(3);
            $book->setTitle($title);
            $book->setSlug($this->slugger->slug($title)->lower());
            $book->setDescription($faker->sentence(20));
            $book->setCreatedAt(new \Datetime);
            $book->setUpdatedAt(new \Datetime);
            $book->setAuthor($this->getReference('auth-ref_' . $i));
            $manager->persist($book);
        }
        $this->addReference(sprintf(self::BOOK_REF, $i), $book);
    }

    public function getDependencies()
    {
        return [
            AuthorFixtures::class
        ];
    }
}