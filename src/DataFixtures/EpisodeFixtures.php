<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
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
            $slugify = new Slugify();
            $episode ->setTitle($faker->title);
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
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
