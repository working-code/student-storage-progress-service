<?php

namespace App\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class MigrationEventSubscriber implements EventSubscriber
{

    public function getSubscribedEvents(): array
    {
        return ['postGenerateSchema'];
    }

    /**
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $scheme = $args->getSchema();

        if (!$scheme->hasNamespace('public')) {
            $scheme->createNamespace('public');
        }
    }
}
