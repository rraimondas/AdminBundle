<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Model;

use Sylius\Component\User\Model\UserInterface;

interface AdminUserInterface extends UserInterface
{
    public const DEFAULT_ADMIN_ROLE = 'ROLE_ADMINISTRATION_ACCESS';

    /**
     * @return string
     */
    public function getFirstName(): ?string;

    /**
     * @param string $firstName
     */
    public function setFirstName(?string $firstName): void;

    /**
     * @return string
     */
    public function getLastName(): ?string;

    /**
     * @param string $lastName
     */
    public function setLastName(?string $lastName): void;

    /**
     * @return string
     */
    public function getLocaleCode(): ?string;
    
    /**
     * @param string $code
     */
    public function setLocaleCode(?string $code): void;
}
