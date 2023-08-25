<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825091514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate_profile ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE candidate_profile ADD CONSTRAINT FK_E8607AEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E8607AEA76ED395 ON candidate_profile (user_id)');
        $this->addSql('ALTER TABLE recruiter_profile ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE recruiter_profile ADD CONSTRAINT FK_4740AFE9A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4740AFE9A76ED395 ON recruiter_profile (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE candidate_profile DROP CONSTRAINT FK_E8607AEA76ED395');
        $this->addSql('DROP INDEX IDX_E8607AEA76ED395');
        $this->addSql('ALTER TABLE candidate_profile ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE recruiter_profile DROP CONSTRAINT FK_4740AFE9A76ED395');
        $this->addSql('DROP INDEX IDX_4740AFE9A76ED395');
        $this->addSql('ALTER TABLE recruiter_profile ALTER user_id SET NOT NULL');
    }
}
