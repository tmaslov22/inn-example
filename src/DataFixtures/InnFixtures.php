<?php

namespace App\DataFixtures;

use App\Entity\Inn;
use Doctrine\Persistence\ObjectManager;
use App\Service\InnGeneratorService;

class InnFixtures extends BaseFixture
{
    public function load(ObjectManager $manager)
    {

        for($i = 0; $i < 25; $i++) {
            $inn = new Inn();
            $inn->setInn(InnGeneratorService::getInn());
            $inn->setPayload($this->getPayload());

            $manager->persist($inn);
        }

        $manager->flush();
    }

    private function getPayload()
    {
        return [
            'status' => $this->getFaker()->boolean,
            'message' => $this->getFaker()->text(300)
        ];
    }

}
