<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ChatType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChatTypeFixture extends Fixture
{
    public const TYPES = [
        'Личный',
        'Беседа',
        'Канал',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TYPES as $type) {
            $chatType = new ChatType($type);

            $this->addReference($type, $chatType);

            $manager->persist($chatType);
        }

        $manager->flush();
    }
}
