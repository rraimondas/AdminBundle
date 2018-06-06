<?php

declare(strict_types=1);

namespace Platform\Bundle\AdminBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Rbac\Model\RoleInterface;
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
     * @var Collection|RoleInterface[]
     */
    private $authorizationRoles;

    /**
     * AdminUser constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->roles = [AdminUserInterface::DEFAULT_ADMIN_ROLE];
        $this->authorizationRoles = new ArrayCollection();
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

    /**
     * @return Collection|RoleInterface[]
     */
    public function getAuthorizationRoles(): Collection
    {
        return $this->authorizationRoles;
    }

    /**
     * @param Collection|RoleInterface[] $authorizationRoles
     *
     * @return $this
     */
    public function setAuthorizationRoles(Collection $authorizationRoles): self
    {
        $this->authorizationRoles = $authorizationRoles;

        return $this;
    }

    /**
     * @param RoleInterface $authorizationRole
     *
     * @return AdminUser
     */
    public function addAuthorizationRole(RoleInterface $authorizationRole): self
    {
        if (false === $this->authorizationRoles->contains($authorizationRole)) {
            $this->authorizationRoles->add($authorizationRole);
        }

        return $this;
    }

    /**
     * @param RoleInterface $authorizationRole
     *
     * @return AdminUser
     */
    public function removeAuthorizationRole(RoleInterface $authorizationRole): self
    {
        $this->authorizationRoles->remove($authorizationRole);

        return $this;
    }

    /**
     * @param string $authorizationRoleCode
     *
     * @return bool
     */
    public function hasAuthorizationRoleCode(string $authorizationRoleCode): bool
    {
        foreach ($this->authorizationRoles as $role) {
            if ($role->getCode() === $authorizationRoleCode) {
                return true;
            }
        }

        return false;
    }
}
