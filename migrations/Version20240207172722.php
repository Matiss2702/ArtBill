<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207172722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD is_paid BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE invoice DROP status');
        $this->addSql('ALTER TABLE invoice DROP vat_rates');
        $this->addSql('ALTER TABLE invoice ALTER customer_id DROP NOT NULL');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN quantity TO quotations_id');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744AA773633 FOREIGN KEY (quotations_id) REFERENCES quotation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90651744AA773633 ON invoice (quotations_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744AA773633');
        $this->addSql('DROP INDEX IDX_90651744AA773633');
        $this->addSql('ALTER TABLE invoice ADD status VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD vat_rates JSON NOT NULL');
        $this->addSql('ALTER TABLE invoice DROP is_paid');
        $this->addSql('ALTER TABLE invoice ALTER customer_id SET NOT NULL');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN quotations_id TO quantity');
    }
}
