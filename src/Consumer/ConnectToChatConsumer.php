<?php

declare(strict_types=1);

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class ConnectToChatConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg): bool|int
    {
        $chatId = $msg->getBody();

        $msg->getChannel()->exchange_declare($chatId, AMQPExchangeType::FANOUT);

        $msg->getChannel()->basic_publish(new AMQPMessage($chatId), 'amq.topic', $chatId);

        $msg->ack();

        return self::MSG_ACK_SENT;
    }
}
