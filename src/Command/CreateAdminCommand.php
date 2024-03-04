<?php

namespace App\Command;

use App\Entity\Admin;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
    private $entityManager;
    private $passwordEncoder;


    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }
    protected function configure()
    {
        $this
            ->setName('app:create-admin')
            ->setDescription('Create an admin admin')
            ->setHelp('This command allows you to create an admin admin')
            ->addArgument('firstName', InputArgument::REQUIRED, 'Admin firstname')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Admin lastName')
            ->addArgument('email', InputArgument::REQUIRED, 'Admin email')
            ->addArgument('password', InputArgument::REQUIRED, 'Admin password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = 'ROLE_ADMIN';


        /*$user = new User();
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();*/

        $admin = new Admin();
        $admin->setEmail($email);
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, $password));
        $admin->setRoles([$role]);
        $admin->setFirstName($firstName);
        $admin->setLastName($lastName);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();


        $output->writeln('Admin created successfully.');

        return Command::SUCCESS;
    }
}
