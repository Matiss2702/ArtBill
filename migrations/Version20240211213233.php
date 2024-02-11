<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211213233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_service (invoice_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(invoice_id, service_id))');
        $this->addSql('CREATE INDEX IDX_1344AC012989F1FD ON invoice_service (invoice_id)');
        $this->addSql('CREATE INDEX IDX_1344AC01ED5CA9E6 ON invoice_service (service_id)');
        $this->addSql('ALTER TABLE invoice_service ADD CONSTRAINT FK_1344AC012989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_service ADD CONSTRAINT FK_1344AC01ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD name VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE company ADD vat_number INT NOT NULL');
        $this->addSql('ALTER TABLE company DROP tva_number');
        $this->addSql('ALTER TABLE company DROP creation_date');
        $this->addSql('ALTER TABLE company DROP bank_statement');
        $this->addSql('ALTER TABLE company ALTER zip_code SET NOT NULL');
        $this->addSql('ALTER TABLE company RENAME COLUMN commercial_name TO bank_information_statement');
        $this->addSql('ALTER TABLE invoice ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD vat_rate10 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD vat_rate20 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD base_vat_rate10 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD base_vat_rate20 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD base_vat_rate0 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER due_date DROP DEFAULT');
        $this->addSql('ALTER TABLE quotation ADD vat_rate10 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD vat_rate20 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD base_vat_rate10 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD base_vat_rate20 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD base_vat_rate0 DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quotation ALTER due_date DROP DEFAULT');
        $this->addSql('ALTER TABLE quotation ALTER due_date DROP NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB93CBC65BC FOREIGN KEY (previous_version_id) REFERENCES quotation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_474A8DB93CBC65BC ON quotation (previous_version_id)');
        $this->addSql('ALTER TABLE service ADD vat_rate INT NOT NULL');
        $this->addSql('ALTER TABLE service ALTER label SET NOT NULL');
        $this->addSql('ALTER TABLE service ALTER price SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice_service DROP CONSTRAINT FK_1344AC012989F1FD');
        $this->addSql('ALTER TABLE invoice_service DROP CONSTRAINT FK_1344AC01ED5CA9E6');
        $this->addSql('DROP TABLE invoice_service');
        $this->addSql('ALTER TABLE company ADD tva_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD creation_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD bank_statement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company DROP name');
        $this->addSql('ALTER TABLE company DROP vat_number');
        $this->addSql('ALTER TABLE company ALTER zip_code DROP NOT NULL');
        $this->addSql('ALTER TABLE company RENAME COLUMN bank_information_statement TO commercial_name');
        $this->addSql('ALTER TABLE service DROP vat_rate');
        $this->addSql('ALTER TABLE service ALTER label DROP NOT NULL');
        $this->addSql('ALTER TABLE service ALTER price DROP NOT NULL');
        $this->addSql('ALTER TABLE invoice DROP date');
        $this->addSql('ALTER TABLE invoice DROP vat_rate10');
        $this->addSql('ALTER TABLE invoice DROP vat_rate20');
        $this->addSql('ALTER TABLE invoice DROP base_vat_rate10');
        $this->addSql('ALTER TABLE invoice DROP base_vat_rate20');
        $this->addSql('ALTER TABLE invoice DROP base_vat_rate0');
        $this->addSql('ALTER TABLE invoice DROP created_at');
        $this->addSql('ALTER TABLE invoice DROP updated_at');
        $this->addSql('ALTER TABLE invoice ALTER due_date SET DEFAULT CURRENT_DATE');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB93CBC65BC');
        $this->addSql('DROP INDEX UNIQ_474A8DB93CBC65BC');
        $this->addSql('ALTER TABLE quotation DROP vat_rate10');
        $this->addSql('ALTER TABLE quotation DROP vat_rate20');
        $this->addSql('ALTER TABLE quotation DROP base_vat_rate10');
        $this->addSql('ALTER TABLE quotation DROP base_vat_rate20');
        $this->addSql('ALTER TABLE quotation DROP base_vat_rate0');
        $this->addSql('ALTER TABLE quotation ALTER due_date SET DEFAULT CURRENT_DATE');
        $this->addSql('ALTER TABLE quotation ALTER due_date SET NOT NULL');
    }
}
