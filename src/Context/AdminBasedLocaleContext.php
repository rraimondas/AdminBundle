<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Context;

use Platform\Bundle\AdminBundle\Model\AdminUserInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdminBasedLocaleContext implements LocaleContextInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getLocaleCode(): string
    {
        $token = $this->tokenStorage->getToken();
        if ($token === null) {
            throw new LocaleNotFoundException();
        }

        $adminUser = $token->getUser();
        if (!$adminUser instanceof AdminUserInterface) {
            throw new LocaleNotFoundException();
        }

        return $adminUser->getLocaleCode();
    }
}
