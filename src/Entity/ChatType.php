<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChatTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\TimeBasedUidInterface;

#[ORM\Entity(repositoryClass: ChatTypeRepository::class)]
#[ORM\Table(name: 'chat_types')]
class ChatType
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private TimeBasedUidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 10, unique: true)]
    private string $name;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
