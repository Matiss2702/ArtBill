<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230120552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quotation_service (quotation_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(quotation_id, service_id))');
        $this->addSql('CREATE INDEX IDX_F1BD9042B4EA4E60 ON quotation_service (quotation_id)');
        $this->addSql('CREATE INDEX IDX_F1BD9042ED5CA9E6 ON quotation_service (service_id)');
        $this->addSql('ALTER TABLE quotation_service ADD CONSTRAINT FK_F1BD9042B4EA4E60 FOREIGN KEY (quotation_id) REFERENCES quotation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation_service ADD CONSTRAINT FK_F1BD9042ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation ALTER date SET DEFAULT CURRENT_DATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE quotation_service DROP CONSTRAINT FK_F1BD9042B4EA4E60');
        $this->addSql('ALTER TABLE quotation_service DROP CONSTRAINT FK_F1BD9042ED5CA9E6');
        $this->addSql('DROP TABLE quotation_service');
        $this->addSql('ALTER TABLE quotation ALTER date DROP DEFAULT');
    }
}
