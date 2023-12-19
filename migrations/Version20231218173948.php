<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218173948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE category ALTER created DROP NOT NULL');
        $this->addSql('ALTER TABLE service ALTER created DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category ALTER created SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE category ALTER created SET NOT NULL');
        $this->addSql('ALTER TABLE service ALTER created SET DEFAULT CURRENT_TIMESTAMP');
    }
}
