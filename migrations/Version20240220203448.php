<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220203448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id UUID NOT NULL, label VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE company (id UUID NOT NULL, commercial_name VARCHAR(255) DEFAULT NULL, siren INT DEFAULT NULL, tva_number INT DEFAULT NULL, share_capital INT DEFAULT NULL, creation_date DATE DEFAULT NULL, bank_statement INT DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094FDB8BBA08 ON company (siren)');
        $this->addSql('COMMENT ON COLUMN company.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE customer (id UUID NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN customer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invoice (id UUID NOT NULL, owner_id UUID NOT NULL, customer_id UUID DEFAULT NULL, company_id UUID NOT NULL, quotations_id UUID NOT NULL, description VARCHAR(255) DEFAULT NULL, amount_ht DOUBLE PRECISION NOT NULL, amount_ttc DOUBLE PRECISION NOT NULL, due_date DATE DEFAULT CURRENT_DATE NOT NULL, is_paid BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_906517447E3C61F9 ON invoice (owner_id)');
        $this->addSql('CREATE INDEX IDX_906517449395C3F3 ON invoice (customer_id)');
        $this->addSql('CREATE INDEX IDX_90651744979B1AD6 ON invoice (company_id)');
        $this->addSql('CREATE INDEX IDX_90651744AA773633 ON invoice (quotations_id)');
        $this->addSql('COMMENT ON COLUMN invoice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice.quotations_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE payment_method (id UUID NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payment_method.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE post (id UUID NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN post.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN post.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE quotation (id UUID NOT NULL, owner_id UUID NOT NULL, company_id UUID NOT NULL, customer_id UUID DEFAULT NULL, previous_version_id UUID DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, amount_ht DOUBLE PRECISION NOT NULL, amount_ttc DOUBLE PRECISION NOT NULL, status VARCHAR(100) DEFAULT \'created\' NOT NULL, date DATE DEFAULT CURRENT_DATE NOT NULL, due_date DATE DEFAULT CURRENT_DATE NOT NULL, version INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_474A8DB97E3C61F9 ON quotation (owner_id)');
        $this->addSql('CREATE INDEX IDX_474A8DB9979B1AD6 ON quotation (company_id)');
        $this->addSql('CREATE INDEX IDX_474A8DB99395C3F3 ON quotation (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_474A8DB93CBC65BC ON quotation (previous_version_id)');
        $this->addSql('COMMENT ON COLUMN quotation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quotation.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quotation.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quotation.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quotation.previous_version_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE quotation_service (quotation_id UUID NOT NULL, service_id UUID NOT NULL, PRIMARY KEY(quotation_id, service_id))');
        $this->addSql('CREATE INDEX IDX_F1BD9042B4EA4E60 ON quotation_service (quotation_id)');
        $this->addSql('CREATE INDEX IDX_F1BD9042ED5CA9E6 ON quotation_service (service_id)');
        $this->addSql('COMMENT ON COLUMN quotation_service.quotation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quotation_service.service_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE reply (id UUID NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN reply.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE service (id UUID NOT NULL, category_id UUID DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, price INT DEFAULT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E19D9AD212469DE2 ON service (category_id)');
        $this->addSql('COMMENT ON COLUMN service.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN service.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, company_id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, is_verified BOOLEAN NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON "user" (company_id)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517447E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744AA773633 FOREIGN KEY (quotations_id) REFERENCES quotation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB97E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB9979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB93CBC65BC FOREIGN KEY (previous_version_id) REFERENCES quotation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation_service ADD CONSTRAINT FK_F1BD9042B4EA4E60 FOREIGN KEY (quotation_id) REFERENCES quotation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation_service ADD CONSTRAINT FK_F1BD9042ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517447E3C61F9');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517449395C3F3');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744979B1AD6');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744AA773633');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB97E3C61F9');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB9979B1AD6');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB99395C3F3');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT FK_474A8DB93CBC65BC');
        $this->addSql('ALTER TABLE quotation_service DROP CONSTRAINT FK_F1BD9042B4EA4E60');
        $this->addSql('ALTER TABLE quotation_service DROP CONSTRAINT FK_F1BD9042ED5CA9E6');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD212469DE2');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649979B1AD6');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE quotation');
        $this->addSql('DROP TABLE quotation_service');
        $this->addSql('DROP TABLE reply');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
