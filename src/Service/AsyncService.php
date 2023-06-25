<?php

namespace App\Service;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class AsyncService
{
    public const RECALCULATE_SKILLS_FOR_USER = 'recalculate_skills_for_user';

    /** @var ProducerInterface[] */
    private array $producers;

    public function __construct()
    {
        $this->producers = [];
    }

    public function addProducer(string $producerName, ProducerInterface $producer): void
    {
        $this->producers[$producerName] = $producer;
    }

    public function publishToExchange(
        string  $producerName,
        string  $message,
        ?string $routingKey = null,
        ?array  $additionalProperties = null,
    ): bool
    {
        if (isset($this->producers[$producerName])) {
            $this->producers[$producerName]->publish($message, $routingKey ?? '', $additionalProperties ?? []);

            return true;
        }

        return false;
    }
}
