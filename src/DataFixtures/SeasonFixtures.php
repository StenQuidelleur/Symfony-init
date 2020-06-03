<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use  Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');
        For ($i=0; $i <= 10; $i++) {
            $season = new Season();
            $season->setNumber($i+1);
            $season->setYear($faker->numberBetween($min = 2000, $max = 2020));
            $season->setDescription($faker->text);
            $season->setProgram($this->getReference('program_' . $faker->numberBetween($min = 0, $max = 5)));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
