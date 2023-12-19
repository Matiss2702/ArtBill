<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219153321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP vat_rates');
        $this->addSql('ALTER TABLE invoice ALTER due_date SET DEFAULT CURRENT_DATE');
        $this->addSql('ALTER TABLE quotation DROP vat_rates');
        $this->addSql('ALTER TABLE quotation ALTER due_date SET DEFAULT CURRENT_DATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice ADD vat_rates TEXT DEFAULT \'[]\' NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER due_date DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN invoice.vat_rates IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE quotation ADD vat_rates TEXT DEFAULT \'[]\' NOT NULL');
        $this->addSql('ALTER TABLE quotation ALTER due_date DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN quotation.vat_rates IS \'(DC2Type:simple_array)\'');
    }
}
