<?php

namespace Platform\Bundle\AdminBundle\Installer\Setup;

use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Languages;

class LocaleSetup
{
    private RepositoryInterface $repository;

    private FactoryInterface $factory;

    private string $locale;

    public function __construct(RepositoryInterface $repository, FactoryInterface $factory, string $locale)
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->locale = trim($locale);
    }

    public function setup(InputInterface $input, OutputInterface $output): LocaleInterface
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

    private function getLanguageName(string $code): string
    {
        return Languages::getName($code);
    }
}
