<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240113093258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_application (id INT AUTO_INCREMENT NOT NULL, job_id_id INT NOT NULL, candidate_id_id INT NOT NULL, approved TINYINT(1) NOT NULL, INDEX IDX_C737C6887E182327 (job_id_id), INDEX IDX_C737C68847A475AB (candidate_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_apply_approve_request (id INT AUTO_INCREMENT NOT NULL, job_application_id INT NOT NULL, request_date DATE DEFAULT NULL, approved TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A4053F32AC7A5A08 (job_application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_application ADD CONSTRAINT FK_C737C6887E182327 FOREIGN KEY (job_id_id) REFERENCES job_advertissement (id)');
        $this->addSql('ALTER TABLE job_application ADD CONSTRAINT FK_C737C68847A475AB FOREIGN KEY (candidate_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE job_apply_approve_request ADD CONSTRAINT FK_A4053F32AC7A5A08 FOREIGN KEY (job_application_id) REFERENCES job_application (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_application DROP FOREIGN KEY FK_C737C6887E182327');
        $this->addSql('ALTER TABLE job_application DROP FOREIGN KEY FK_C737C68847A475AB');
        $this->addSql('ALTER TABLE job_apply_approve_request DROP FOREIGN KEY FK_A4053F32AC7A5A08');
        $this->addSql('DROP TABLE job_application');
        $this->addSql('DROP TABLE job_apply_approve_request');
    }
}
