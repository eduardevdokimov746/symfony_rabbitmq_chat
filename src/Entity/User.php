<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\TimeBasedUidInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private TimeBasedUidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 20, unique: true)]
    private string $login;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::STRING, length: 60)]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 60, nullable: true, options: ['default' => null])]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private string $avatar;

    #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'users')]
    private ArrayCollection|PersistentCollection $chats;

    public function __construct(string $login, string $password, string $firstName, string $avatar)
    {
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setFirstName($firstName);
        $this->setAvatar($avatar);

        $this->chats = new ArrayCollection();
    }

    public function getId(): TimeBasedUidInterface
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $fistName): void
    {
        $this->firstName = $fistName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function addChat(Chat $chat): void
    {
        $this->chats->add($chat);

        $chat->addUser($this);
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getLogin();
    }

    public function getFullName(): string
    {
        return $this->getFirstName().($this->getLastName() ? ' '.$this->getLastName() : '');
    }
}
