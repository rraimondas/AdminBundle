<?php

namespace Platform\Bundle\AdminBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Model\UserInterface;

class CanonicalizerListener
{
    /**
     * @var CanonicalizerInterface
     */
    private $canonicalizer;

    /**
     * @param CanonicalizerInterface $canonicalizer
     */
    public function __construct(CanonicalizerInterface $canonicalizer)
    {
        $this->canonicalizer = $canonicalizer;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->canonicalize($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->canonicalize($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    private function canonicalize(LifecycleEventArgs $event)
    {
        $item = $event->getEntity();

        if ($item instanceof UserInterface) {
            $item->setUsernameCanonical($this->canonicalizer->canonicalize($item->getUsername()));
            $item->setEmailCanonical($this->canonicalizer->canonicalize($item->getEmail()));
        }
    }
}
