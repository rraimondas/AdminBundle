<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Model;

use Sylius\Component\User\Model\UserInterface;

interface AdminUserInterface extends UserInterface
{
    public const DEFAULT_ADMIN_ROLE = 'ROLE_ADMINISTRATION_ACCESS';

    public function getFirstName(): ?string;

    public function setFirstName(?string $firstName): void;

    public function getLastName(): ?string;

    public function setLastName(?string $lastName): void;

    public function getLocaleCode(): ?string;

    public function setLocaleCode(?string $code): void;
}
