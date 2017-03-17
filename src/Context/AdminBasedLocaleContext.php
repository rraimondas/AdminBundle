<?php

namespace Platform\Bundle\AdminBundle\Context;

use Platform\Bundle\AdminBundle\Model\AdminUserInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdminBasedLocaleContext implements LocaleContextInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode()
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw new LocaleNotFoundException();
        }

        $adminUser = $token->getUser();
        if (false === $adminUser instanceof AdminUserInterface) {
            throw new LocaleNotFoundException();
        }

        return $adminUser->getLocaleCode();
    }
}
