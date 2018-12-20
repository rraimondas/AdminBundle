<?php

namespace Platform\Bundle\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Platform\Bundle\AdminBundle\Installer\Setup\LocaleSetup;
use Platform\Bundle\AdminBundle\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

class SetupCommand extends AbstractInstallCommand
{
    const DEFAULT_USER_EMAIL = 'admin-platform@example.com';
    const DEFAULT_USER_PASSWORD = 'admin-platform';

    /**
     * @var LocaleSetup
     */
    private $localeSetup;

    /**
     * @var EntityManagerInterface
     */
    private $userManager;

    /**
     * @var FactoryInterface
     */
    private $userFactory;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(LocaleSetup $localeSetup, EntityManagerInterface $userManager, FactoryInterface $userFactory, UserRepositoryInterface $userRepository, ValidatorInterface $validator)
    {
        parent::__construct();

        $this->localeSetup = $localeSetup;
        $this->userManager = $userManager;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('admin-platform:install:setup')
            ->setDescription('Admin platform configuration setup.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows user to configure basic Admin platform data.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locale = $this->localeSetup->setup($input, $output);

        $this->setupAdministratorUser($input, $output, $locale->getCode());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $localeCode
     *
     * @return int
     */
    private function setupAdministratorUser(InputInterface $input, OutputInterface $output, $localeCode)
    {
        $outputStyle = new SymfonyStyle($input, $output);
        $outputStyle->writeln('Create your administrator account.');

        try {
            $user = $this->configureNewUser($this->userFactory->createNew(), $input, $output);
        } catch (\InvalidArgumentException $exception) {
            return 0;
        }

        $user->setEnabled(true);
        $user->setLocaleCode($localeCode);

        $this->userManager->persist($user);
        $this->userManager->flush();
        
        $outputStyle->writeln('<info>Administrator account successfully registered.</info>');
        $outputStyle->newLine();
    }

    /**
     * @param AdminUserInterface $user
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return AdminUserInterface
     */
    private function configureNewUser(AdminUserInterface $user, InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('no-interaction')) {
            Assert::null($this->userRepository->findOneByEmail(self::DEFAULT_USER_EMAIL));
            
            $user->setEmail(self::DEFAULT_USER_EMAIL);
            $user->setPlainPassword(self::DEFAULT_USER_PASSWORD);

            return $user;
        }

        $questionHelper = $this->getHelper('question');

        do {
            $question = $this->createEmailQuestion($output);
            $email = $questionHelper->ask($input, $output, $question);
            $exists = null !== $this->userRepository->findOneByEmail($email);
            
            if ($exists) {
                $output->writeln('<error>E-Mail is already in use!</error>');
            }
        } while ($exists);

        $user->setEmail($email);
        $user->setPlainPassword($this->getAdministratorPassword($input, $output));

        return $user;
    }

    /**
     * @param OutputInterface $output
     *
     * @return Question
     */
    private function createEmailQuestion(OutputInterface $output)
    {
        return (new Question('E-mail:'))
            ->setValidator(function ($value) use ($output) {
                /** @var ConstraintViolationListInterface $errors */
                $errors = $this->validator->validate((string) $value, [new Email(), new NotBlank()]);
                foreach ($errors as $error) {
                    throw new \DomainException($error->getMessage());
                }

                return $value;
            })
            ->setMaxAttempts(3);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    private function getAdministratorPassword(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $validator = $this->getPasswordQuestionValidator($output);

        do {
            $passwordQuestion = $this->createPasswordQuestion('Choose password:', $validator);
            $confirmPasswordQuestion = $this->createPasswordQuestion('Confirm password:', $validator);
            $password = $questionHelper->ask($input, $output, $passwordQuestion);
            $repeatedPassword = $questionHelper->ask($input, $output, $confirmPasswordQuestion);
            
            if ($repeatedPassword !== $password) {
                $output->writeln('<error>Passwords do not match!</error>');
            }
        } while ($repeatedPassword !== $password);

        return $password;
    }

    /**
     * @param OutputInterface $output
     *
     * @return \Closure
     */
    private function getPasswordQuestionValidator(OutputInterface $output)
    {
        return function ($value) use ($output) {
            /** @var ConstraintViolationListInterface $errors */
            $errors = $this->validator->validate($value, [new NotBlank()]);
            foreach ($errors as $error) {
                throw new \DomainException($error->getMessage());
            }

            return $value;
        };
    }
    /**
     * @param string $message
     * @param \Closure $validator
     *
     * @return Question
     */
    private function createPasswordQuestion($message, \Closure $validator)
    {
        return (new Question($message))
            ->setValidator($validator)
            ->setMaxAttempts(3)
            ->setHidden(true)
            ->setHiddenFallback(false);
    }
}
