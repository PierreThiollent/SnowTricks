<?php

namespace App\DataFixtures;

use \Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // TODO: Implement load() method.

        for ($i = 0; $i < 20; $i++) {

        }
    }
}