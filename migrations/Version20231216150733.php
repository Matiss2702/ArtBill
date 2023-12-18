<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231216150733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customer (id INT NOT NULL, address_id INT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_81398E09F5B7AF75 ON customer (address_id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094FDB8BBA08 ON company (siren)');
        $this->addSql('ALTER TABLE invoice ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD amount_ht DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD amount_ttc DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE invoice DROP price');
        $this->addSql('ALTER TABLE invoice DROP date');
        $this->addSql('ALTER TABLE invoice DROP is_deposit');
        $this->addSql('ALTER TABLE invoice DROP client');
        $this->addSql('ALTER TABLE invoice DROP company');
        $this->addSql('ALTER TABLE invoice ALTER status TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE invoice ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN vat_rate TO description');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517447E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_906517447E3C61F9 ON invoice (owner_id)');
        $this->addSql('CREATE INDEX IDX_906517449395C3F3 ON invoice (customer_id)');
        $this->addSql('CREATE INDEX IDX_90651744979B1AD6 ON invoice (company_id)');
        $this->addSql('ALTER TABLE quotation ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD amount_ht DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD amount_ttc DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD due_date DATE NOT NULL');
        $this->addSql('ALTER TABLE quotation DROP price');
        $this->addSql('ALTER TABLE quotation DROP vat_rate');
        $this->addSql('ALTER TABLE quotation DROP client');
        $this->addSql('ALTER TABLE quotation DROP company');
        $this->addSql('ALTER TABLE quotation ALTER status TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE quotation ALTER status SET DEFAULT \'created\'');
        $this->addSql('ALTER TABLE quotation ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE quotation ALTER date SET NOT NULL');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB97E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB9979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_474A8DB97E3C61F9 ON quotation (owner_id)');
        $this->addSql('CREATE INDEX IDX_474A8DB9979B1AD6 ON quotation (company_id)');
        $this->addSql('CREATE INDEX IDX_474A8DB99395C3F3 ON quotation (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517449395C3F3');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB99395C3F3');
        $this->addSql('DROP SEQUENCE customer_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, address INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E09F5B7AF75');
        $this->addSql('DROP TABLE customer');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB97E3C61F9');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB9979B1AD6');
        $this->addSql('DROP INDEX IDX_474A8DB97E3C61F9');
        $this->addSql('DROP INDEX IDX_474A8DB9979B1AD6');
        $this->addSql('DROP INDEX IDX_474A8DB99395C3F3');
        $this->addSql('ALTER TABLE quotation ADD price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quotation ADD vat_rate VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE quotation ADD client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quotation ADD company INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quotation DROP owner_id');
        $this->addSql('ALTER TABLE quotation DROP company_id');
        $this->addSql('ALTER TABLE quotation DROP customer_id');
        $this->addSql('ALTER TABLE quotation DROP amount_ht');
        $this->addSql('ALTER TABLE quotation DROP amount_ttc');
        $this->addSql('ALTER TABLE quotation DROP quantity');
        $this->addSql('ALTER TABLE quotation DROP due_date');
        $this->addSql('ALTER TABLE quotation ALTER status TYPE INT');
        $this->addSql('ALTER TABLE quotation ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE quotation ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE quotation ALTER status TYPE INT');
        $this->addSql('ALTER TABLE quotation ALTER date DROP NOT NULL');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517447E3C61F9');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744979B1AD6');
        $this->addSql('DROP INDEX IDX_906517447E3C61F9');
        $this->addSql('DROP INDEX IDX_906517449395C3F3');
        $this->addSql('DROP INDEX IDX_90651744979B1AD6');
        $this->addSql('ALTER TABLE invoice ADD price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD is_deposit BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD company INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice DROP owner_id');
        $this->addSql('ALTER TABLE invoice DROP customer_id');
        $this->addSql('ALTER TABLE invoice DROP company_id');
        $this->addSql('ALTER TABLE invoice DROP amount_ht');
        $this->addSql('ALTER TABLE invoice DROP amount_ttc');
        $this->addSql('ALTER TABLE invoice DROP quantity');
        $this->addSql('ALTER TABLE invoice ALTER status TYPE INT');
        $this->addSql('ALTER TABLE invoice ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER status TYPE INT');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN description TO vat_rate');
        $this->addSql('DROP INDEX UNIQ_4FBF094FDB8BBA08');
    }
}
