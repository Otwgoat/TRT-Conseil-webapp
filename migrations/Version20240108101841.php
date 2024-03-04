<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108101841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consultant (id INT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruiter (id INT NOT NULL, company_name VARCHAR(255) NOT NULL, company_adress VARCHAR(355) NOT NULL, approved TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consultant ADD CONSTRAINT FK_441282A1BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recruiter ADD CONSTRAINT FK_DE8633D8BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultant DROP FOREIGN KEY FK_441282A1BF396750');
        $this->addSql('ALTER TABLE recruiter DROP FOREIGN KEY FK_DE8633D8BF396750');
        $this->addSql('DROP TABLE consultant');
        $this->addSql('DROP TABLE recruiter');
    }
}
