<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240111093924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_advertissement (id INT AUTO_INCREMENT NOT NULL, recruiter_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(500) NOT NULL, city VARCHAR(255) NOT NULL, planning VARCHAR(255) NOT NULL, salary INT NOT NULL, approved TINYINT(1) NOT NULL, INDEX IDX_CC5B0DEAA2B5DF02 (recruiter_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_advertissement_user (job_advertissement_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_471EF8E28B2366BB (job_advertissement_id), INDEX IDX_471EF8E2A76ED395 (user_id), PRIMARY KEY(job_advertissement_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_advertissement ADD CONSTRAINT FK_CC5B0DEAA2B5DF02 FOREIGN KEY (recruiter_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE job_advertissement_user ADD CONSTRAINT FK_471EF8E28B2366BB FOREIGN KEY (job_advertissement_id) REFERENCES job_advertissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_advertissement_user ADD CONSTRAINT FK_471EF8E2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_advertissement DROP FOREIGN KEY FK_CC5B0DEAA2B5DF02');
        $this->addSql('ALTER TABLE job_advertissement_user DROP FOREIGN KEY FK_471EF8E28B2366BB');
        $this->addSql('ALTER TABLE job_advertissement_user DROP FOREIGN KEY FK_471EF8E2A76ED395');
        $this->addSql('DROP TABLE job_advertissement');
        $this->addSql('DROP TABLE job_advertissement_user');
    }
}
