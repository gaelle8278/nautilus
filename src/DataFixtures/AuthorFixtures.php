<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Author;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $author = new Author();
       $author->setFirstname("Raymond F.");
       $author->setLastname("Feist");
       $manager->persist($author);

       $manager->flush();
    }
}
