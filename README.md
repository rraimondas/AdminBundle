# PlatformAdminBundle

This Bundle implement admin platform based on Sylius components.

## Installation

**Step 1**. Install via [Composer](https://getcomposer.org/)

```
composer require admin-platform/admin-bundle "dev-master"
```

**Step 2**. Add to `AppKernel.php`

```php
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
             // ...
             new Platform\Bundle\AdminBundle\PlatformAdminBundle(),
             // ...
        ];
    }
}
