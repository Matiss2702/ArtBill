<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121173002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quotation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, address INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, commercial_name VARCHAR(255) DEFAULT NULL, siren INT DEFAULT NULL, tva_number INT DEFAULT NULL, share_capital INT DEFAULT NULL, address INT DEFAULT NULL, creation_date DATE DEFAULT NULL, bank_statement INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, price INT DEFAULT NULL, vat_rate VARCHAR(255) DEFAULT NULL, status INT DEFAULT NULL, date DATE DEFAULT NULL, due_date DATE NOT NULL, is_deposit BOOLEAN DEFAULT NULL, client INT DEFAULT NULL, company INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE payment_method (id INT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE quotation (id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, price INT DEFAULT NULL, vat_rate VARCHAR(255) DEFAULT NULL, status INT DEFAULT NULL, date DATE DEFAULT NULL, client INT DEFAULT NULL, company INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, label VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, price INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "user" ADD is_verified BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD company INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_method_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quotation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE quotation');
        $this->addSql('DROP TABLE service');
        $this->addSql('ALTER TABLE "user" DROP is_verified');
        $this->addSql('ALTER TABLE "user" DROP company');
    }
}
