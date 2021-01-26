<?php


namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->setFaker();
    }

    protected function getFaker(): Generator
    {
        return $this->faker;
    }

    private function setFaker(): void
    {
        $this->faker = Factory::create('ru_RU');
    }
    
}