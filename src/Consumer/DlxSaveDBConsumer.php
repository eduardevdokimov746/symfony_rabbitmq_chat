<?php

declare(strict_types=1);

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class DlxSaveDBConsumer implements ConsumerInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function execute(AMQPMessage $msg): bool|int
    {
        $this->logger->warning('Bad message', json_decode($msg->getBody(), true));

        $msg->ack();

        return self::MSG_ACK_SENT;
    }
}
