<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230823082257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creation of tables Application, CandidateProfile, JobOffer, RecruiterProfile, Role and User';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE candidate_profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE job_offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recruiter_profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE application (id INT NOT NULL, job_offer_id INT NOT NULL, candidate_id INT NOT NULL, application_validation BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE candidate_profile (id INT NOT NULL, user_id INT NOT NULL, last_name VARCHAR(50) DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, resume VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE job_offer (id INT NOT NULL, recruiter_id INT NOT NULL, job_title VARCHAR(100) NOT NULL, job_location VARCHAR(255) NOT NULL, contract_type VARCHAR(30) NOT NULL, job_description VARCHAR(2000) NOT NULL, candidate_experience VARCHAR(500) DEFAULT NULL, working_hours VARCHAR(150) NOT NULL, salary VARCHAR(50) NOT NULL, publish_validation BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE recruiter_profile (id INT NOT NULL, user_id INT NOT NULL, company_name VARCHAR(50) DEFAULT NULL, company_address VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(50) NOT NULL, role_id INT NOT NULL, account_validation BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE candidate_profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE job_offer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recruiter_profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE candidate_profile');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE recruiter_profile');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE "user"');
    }
}
