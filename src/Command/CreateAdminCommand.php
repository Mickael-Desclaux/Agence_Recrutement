<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
protected static $defaultName = 'app:create-admin';

private EntityManagerInterface $entityManager;
private UserPasswordHasherInterface $passwordHasher;

public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
{
$this->entityManager = $entityManager;
$this->passwordHasher = $passwordHasher;

parent::__construct();
}

protected function configure(): void
{
$this
->setDescription('Creates a new admin.')
->setHelp('This command allows you to create an admin')
->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
->addArgument('password', InputArgument::REQUIRED, 'The password of the user.')
;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
$user = new User();
$user->setEmail($input->getArgument('email'));

$password = $this->passwordHasher->hashPassword($user, $input->getArgument('password'));
$user->setPassword($password);
$user->setRole('ROLE_ADMIN');
$user->setUserValidation(true);

$this->entityManager->persist($user);
$this->entityManager->flush();

$output->writeln('User successfully generated!');

return Command::SUCCESS;
}
}