<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Model;

use Sylius\Component\User\Model\User;

class AdminUser extends User implements AdminUserInterface
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $localeCode;

    /**
     * AdminUser constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->roles = [AdminUserInterface::DEFAULT_ADMIN_ROLE];
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocaleCode(?string $code): void
    {
        $this->localeCode = $code;
    }
}
