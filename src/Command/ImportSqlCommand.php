<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportSqlCommand extends Command
{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setName('app:import-sql')
            ->setDescription('Imports the SQL tables file');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = file_get_contents('src/Command/CreateTables.sql');
        $this->connection->executeQuery($sql);
        $output->writeln('SQL file has been imported successfully.');
        return Command::SUCCESS;
    }
}
