<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Chat $chat */
        $chat = $this->getReference('chat');

        /** @var User $user1 */
        $user1 = $this->getReference('max');

        /** @var User $user2 */
        $user2 = $this->getReference('bob');

        $body = 'Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for'.
            ' previewing layouts and visual mockups.';

        $manager->persist(new Message($chat, $user1, $body, new DateTimeImmutable('2023-01-06 10:11', new DateTimeZone('Europe/Moscow'))));
        $manager->persist(new Message($chat, $user2, $body, new DateTimeImmutable('2023-01-06 10:12', new DateTimeZone('Europe/Moscow'))));
        $manager->persist(new Message($chat, $user1, $body, new DateTimeImmutable('2023-01-06 10:13', new DateTimeZone('Europe/Moscow'))));
        $manager->persist(new Message($chat, $user2, $body, new DateTimeImmutable('2023-01-06 10:14', new DateTimeZone('Europe/Moscow'))));

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixture::class];
    }
}
