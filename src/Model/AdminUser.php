<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Model;

use Sylius\Component\User\Model\User;

class AdminUser extends User implements AdminUserInterface
{
    protected ?string $firstName = null;

    protected ?string $lastName = null;

    protected ?string $localeCode = null;

    public function __construct()
    {
        parent::__construct();

        $this->roles = [AdminUserInterface::DEFAULT_ADMIN_ROLE];
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    public function setLocaleCode(?string $code): void
    {
        $this->localeCode = $code;
    }
}
