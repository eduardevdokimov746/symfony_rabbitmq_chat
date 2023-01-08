<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getMessages(string $chatId)
    {
        return $this->createQueryBuilder('m')
            ->select('m, c, u')
            ->join('m.chat', 'c')
            ->join('c.users', 'u')
            ->where('m.chat = :chatId')
            ->setParameter('chatId', $chatId)
            ->orderBy('m.publishedAt', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }
}
