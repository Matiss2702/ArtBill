<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222153755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP CONSTRAINT fk_4fbf094ff5b7af75');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT fk_81398e09f5b7af75');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP INDEX idx_4fbf094ff5b7af75');
        $this->addSql('ALTER TABLE company ADD street VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD zip_code INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company DROP address_id');
        $this->addSql('DROP INDEX idx_81398e09f5b7af75');
        $this->addSql('ALTER TABLE customer DROP address_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE company ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE company DROP street');
        $this->addSql('ALTER TABLE company DROP city');
        $this->addSql('ALTER TABLE company DROP zip_code');
        $this->addSql('ALTER TABLE company DROP country');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT fk_4fbf094ff5b7af75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4fbf094ff5b7af75 ON company (address_id)');
        $this->addSql('ALTER TABLE customer ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT fk_81398e09f5b7af75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_81398e09f5b7af75 ON customer (address_id)');
    }
}
