<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\User;
use App\Service\AvatarService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private AvatarService $avatarService
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            'max',
            password_hash('123', PASSWORD_DEFAULT),
            'Max',
            $this->avatarService->make('Max', 'Maxovich')
        );

        $user1 = new User(
            'bob',
            password_hash('123', PASSWORD_DEFAULT),
            'Bob',
            $this->avatarService->make('Bob', 'Bobovich')
        );

        $user->setLastName('Maxovich');
        $user1->setLastName('Bobovich');

        $this->setReference('max', $user);
        $this->setReference('bob', $user1);

        /** @var Chat $chat */
        $chat = $this->getReference('chat');

        $user->addChat($chat);
        $user1->addChat($chat);

        foreach ([$user, $user1] as $item) {
            $manager->persist($item);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ChatFixture::class];
    }
}
