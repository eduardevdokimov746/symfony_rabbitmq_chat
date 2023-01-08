<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\ChatType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChatFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var ChatType $chatType */
        $chatType = $this->getReference(ChatTypeFixture::TYPES[0]);

        $chat = new Chat($chatType);

        $this->setReference('chat', $chat);

        $manager->persist($chat);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ChatTypeFixture::class];
    }
}
