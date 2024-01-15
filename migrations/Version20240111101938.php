<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240111101938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_approve_request (id INT AUTO_INCREMENT NOT NULL, job_id_id INT NOT NULL, request_date DATE DEFAULT NULL, approved TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5B12E0687E182327 (job_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_approve_request ADD CONSTRAINT FK_5B12E0687E182327 FOREIGN KEY (job_id_id) REFERENCES job_advertissement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_approve_request DROP FOREIGN KEY FK_5B12E0687E182327');
        $this->addSql('DROP TABLE job_approve_request');
    }
}
