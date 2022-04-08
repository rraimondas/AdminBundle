<?php

namespace Platform\Bundle\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Platform\Bundle\AdminBundle\Installer\Setup\LocaleSetup;
use Platform\Bundle\AdminBundle\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

class SetupCommand extends AbstractInstallCommand
{
    protected const DEFAULT_USER_EMAIL = 'admin-platform@example.com';
    protected const DEFAULT_USER_PASSWORD = 'admin-platform';

    private LocaleSetup $localeSetup;

    private EntityManagerInterface $userManager;

    private FactoryInterface $userFactory;

    private UserRepositoryInterface $userRepository;

    private ValidatorInterface $validator;

    public function __construct(
        LocaleSetup $localeSetup,
        EntityManagerInterface $userManager,
        FactoryInterface $userFactory,
        UserRepositoryInterface $userRepository,
        ValidatorInterface $validator
    ) {
        parent::__construct();

        $this->localeSetup = $localeSetup;
        $this->userManager = $userManager;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    protected function configure(): void
    {
        $this
            ->setName('admin-platform:install:setup')
            ->setDescription('Admin platform configuration setup.')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> command allows user to configure basic Admin platform data.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $locale = $this->localeSetup->setup($input, $output);

        $this->setupAdministratorUser($input, $output, $locale->getCode());

        return Command::SUCCESS;
    }

    private function setupAdministratorUser(InputInterface $input, OutputInterface $output, ?string $localeCode): void
    {
        $outputStyle = new SymfonyStyle($input, $output);
        $outputStyle->writeln('Create your administrator account.');

        try {
            $user = $this->configureNewUser($this->userFactory->createNew(), $input, $output);
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        $user->setEnabled(true);
        $user->setLocaleCode($localeCode);

        $this->userManager->persist($user);
        $this->userManager->flush();

        $outputStyle->writeln('<info>Administrator account successfully registered.</info>');
        $outputStyle->newLine();
    }

    private function configureNewUser(
        AdminUserInterface $user,
        InputInterface $input,
        OutputInterface $output
    ): AdminUserInterface {
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

    private function createEmailQuestion(OutputInterface $output): Question
    {
        return (new Question('E-mail:'))
            ->setValidator(
                function ($value) use ($output) {
                    $errors = $this->validator->validate((string)$value, [new Email(), new NotBlank()]);
                    foreach ($errors as $error) {
                        throw new \DomainException($error->getMessage());
                    }

                    return $value;
                }
            )
            ->setMaxAttempts(3);
    }

    private function getAdministratorPassword(InputInterface $input, OutputInterface $output): string
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $validator = $this->getPasswordQuestionValidator();

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

    private function getPasswordQuestionValidator()
    {
        return function ($value) {
            $errors = $this->validator->validate($value, [new NotBlank()]);
            foreach ($errors as $error) {
                throw new \DomainException($error->getMessage());
            }

            return $value;
        };
    }

    private function createPasswordQuestion(string $message, \Closure $validator): Question
    {
        return (new Question($message))
            ->setValidator($validator)
            ->setMaxAttempts(3)
            ->setHidden(true)
            ->setHiddenFallback(false);
    }
}
