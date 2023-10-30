<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('The Dark Knight');
        $movie->setReleaseYear(2008);
        $movie->setDescription('This is the description of The Dark Knight');
        $movie->setImagePath('https://cdn.pixabay.com/photo/2023/09/25/14/09/ai-generated-8275155_1280.jpg');
        // Add Data to Pivot Table
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));
        $manager->persist($movie);


        $movie2 = new Movie();
        $movie2->setTitle('Avengers Endgame');
        $movie2->setReleaseYear(2019);
        $movie2->setDescription('This is the description of Avengers Endgame');
        $movie2->setImagePath('https://cdn.pixabay.com/photo/2017/07/19/17/26/gabriel-2519793_1280.jpg');
        $movie2->addActor($this->getReference('actor_3'));
        $movie2->addActor($this->getReference('actor_4'));
        $manager->persist($movie2);

        $manager->flush();
    }
}
