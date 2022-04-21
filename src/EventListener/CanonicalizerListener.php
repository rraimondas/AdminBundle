<?php

namespace Platform\Bundle\AdminBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Model\UserInterface;

class CanonicalizerListener
{
    private CanonicalizerInterface $canonicalizer;

    public function __construct(CanonicalizerInterface $canonicalizer)
    {
        $this->canonicalizer = $canonicalizer;
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->canonicalize($event);
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->canonicalize($event);
    }

    private function canonicalize(LifecycleEventArgs $event): void
    {
        $item = $event->getEntity();

        if (!$item instanceof UserInterface) {
            return;
        }

        $item->setUsernameCanonical($this->canonicalizer->canonicalize($item->getUsername()));
        $item->setEmailCanonical($this->canonicalizer->canonicalize($item->getEmail()));
    }
}
