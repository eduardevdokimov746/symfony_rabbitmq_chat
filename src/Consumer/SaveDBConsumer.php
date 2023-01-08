<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class SaveDBConsumer implements ConsumerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function execute(AMQPMessage $msg): int|bool
    {
        $body = json_decode($msg->getBody(), true);

        $chat = $this->entityManager->find(Chat::class, $body['chat']);
        $sender = $this->entityManager->find(User::class, $body['sender']['id']);
        $text = $body['text'];
        $publishedAt = new DateTimeImmutable('@'.$body['time']['timestamp'], new DateTimeZone($body['time']['timezone']));

        if (null === $sender) {
            $msg->nack();

            return self::MSG_ACK_SENT;
        }

        $message = new Message($chat, $sender, $text, $publishedAt);

        $this->entityManager->persist($message);

        $this->entityManager->flush();

        $msg->getChannel()->basic_publish(new AMQPMessage($msg->getBody()), $body['chat']);

        $msg->ack();

        return self::MSG_ACK_SENT;
    }
}
