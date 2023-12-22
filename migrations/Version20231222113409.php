<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222113409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD street VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD zip_code INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD country VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer DROP street');
        $this->addSql('ALTER TABLE customer DROP city');
        $this->addSql('ALTER TABLE customer DROP zip_code');
        $this->addSql('ALTER TABLE customer DROP country');
    }
}
