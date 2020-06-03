<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use  Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');
        For ($i=0; $i <= 10; $i++) {
            $episode = new Episode();
            $episode ->setTitle($faker->title);
            $episode ->setNumber($i+1);
            $episode ->setSynopsis($faker->text);
            $episode ->setSeason($this->getReference('season_' . $faker->numberBetween($min = 0, $max = 9)));
            $manager->persist($episode );
            $this->addReference('episode_' . $i, $episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
