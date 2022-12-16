<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class SidebarSubscriber implements EventSubscriberInterface
{
    private TagAwareAdapterInterface $cache;

    public function __construct(TagAwareAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
            Events::postRemove,
            Events::postPersist
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->invalidCache($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->invalidCache($args);
    }

    private function postRemove(LifecycleEventArgs $args)
    {
        $this->invalidCache($args);
    }

    public function invalidCache(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof Comment) return;
        if($entity instanceof Article) $this->cache->invalidateTags(['articles']);
        if($entity instanceof Category) $this->cache->invalidateTags(['categories']);
    }
}