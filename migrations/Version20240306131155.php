<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306131155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('ALTER TABLE service ADD company_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN service.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E19D9AD2979B1AD6 ON service (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE payment_method (id UUID NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payment_method.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2979B1AD6');
        $this->addSql('DROP INDEX IDX_E19D9AD2979B1AD6');
        $this->addSql('ALTER TABLE service DROP company_id');
    }
}
