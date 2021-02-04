<?php

namespace App\EventSubscriber;

use App\Entity\Traits\TimestampsTrait;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * TimestampsSubscriber handles created and updated timestamps events.
 */
class TimestampsSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    /**
     * Trigger before entity persist.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var TimestampsTrait $entity */
        $entity = $args->getEntity();

        if (!method_exists($entity, 'setCreatedAt') || !method_exists($entity, 'setUpdatedAt')) {
            return;
        }

        $entity->setCreatedAt(new DateTime());
        $entity->setUpdatedAt(new DateTime());
    }

    /**
     * Trigger before entity update.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        /** @var TimestampsTrait $entity */
        $entity = $args->getEntity();

        if (!method_exists($entity, 'setCreatedAt') || !method_exists($entity, 'setUpdatedAt')) {
            return;
        }

        $entity->setUpdatedAt(new DateTime());
    }
}