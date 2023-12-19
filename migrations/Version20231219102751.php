<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219102751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE company ADD created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE company DROP address');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4FBF094FF5B7AF75 ON company (address_id)');
        $this->addSql('ALTER TABLE customer ADD created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD vat_rates TEXT DEFAULT \'[]\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN invoice.vat_rates IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE quotation ADD created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD vat_rates TEXT DEFAULT \'[]\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN quotation.vat_rates IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer DROP created');
        $this->addSql('ALTER TABLE invoice DROP created');
        $this->addSql('ALTER TABLE invoice DROP vat_rates');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094FF5B7AF75');
        $this->addSql('DROP INDEX IDX_4FBF094FF5B7AF75');
        $this->addSql('ALTER TABLE company ADD address INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company DROP address_id');
        $this->addSql('ALTER TABLE company DROP created');
        $this->addSql('ALTER TABLE quotation DROP created');
        $this->addSql('ALTER TABLE quotation DROP vat_rates');
    }
}
