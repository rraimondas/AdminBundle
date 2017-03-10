<?php

namespace Platform\Bundle\AdminBundle\Installer\Setup;

use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Intl;

class LocaleSetup
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var string
     */
    private $locale;

    /**
     * @param RepositoryInterface $repository
     * @param FactoryInterface $factory
     * @param string $locale
     */
    public function __construct(RepositoryInterface $repository, FactoryInterface $factory, $locale)
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->locale = trim($locale);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return LocaleInterface
     */
    public function setup(InputInterface $input, OutputInterface $output)
    {
        $name = $this->getLanguageName($this->locale);
        $output->writeln(sprintf('Adding <info>%s</info> locale.', $name));
        $existingLocale = $this->repository->findOneBy(['code' => $this->locale]);

        if (null !== $existingLocale) {
            return $existingLocale;
        }

        /** @var LocaleInterface $locale */
        $locale = $this->factory->createNew();
        $locale->setCode($this->locale);
        $this->repository->add($locale);

        return $locale;
    }

    /**
     * @param string $code
     *
     * @return string|null
     */
    private function getLanguageName($code)
    {
        return Intl::getLanguageBundle()->getLanguageName($code);
    }
}
