<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829090637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer DROP CONSTRAINT fk_288a3a4e156be243');
        $this->addSql('DROP INDEX idx_288a3a4e156be243');
        $this->addSql('ALTER TABLE job_offer ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE job_offer DROP recruiter_id');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_288A3A4EA76ED395 ON job_offer (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE job_offer DROP CONSTRAINT FK_288A3A4EA76ED395');
        $this->addSql('DROP INDEX IDX_288A3A4EA76ED395');
        $this->addSql('ALTER TABLE job_offer ADD recruiter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job_offer DROP user_id');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT fk_288a3a4e156be243 FOREIGN KEY (recruiter_id) REFERENCES recruiter_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_288a3a4e156be243 ON job_offer (recruiter_id)');
    }
}
