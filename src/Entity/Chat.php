<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\TimeBasedUidInterface;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[ORM\Table(name: 'chats')]
class Chat
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private TimeBasedUidInterface $id;

    #[ORM\ManyToOne(targetEntity: ChatType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ChatType $chatType;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'chats')]
    #[ORM\JoinTable(name: 'users_chats')]
    private ArrayCollection|PersistentCollection $users;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'chat')]
    private ArrayCollection|PersistentCollection $messages;

    public function __construct(ChatType $chatType)
    {
        $this->setChatType($chatType);

        $this->users = new ArrayCollection();
    }

    public function getChatType(): ChatType
    {
        return $this->chatType;
    }

    public function setChatType(ChatType $chatType): void
    {
        $this->chatType = $chatType;
    }

    public function getUsers(): ArrayCollection|PersistentCollection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        $this->users->add($user);
    }
}
