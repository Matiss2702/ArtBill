<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030184915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8d93d6491677722f');
        $this->addSql('DROP INDEX uniq_8d93d649d4e6f81');
        $this->addSql('DROP INDEX uniq_8d93d6494fbf094f');
        $this->addSql('ALTER TABLE "user" ADD created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER COLUMN birthdate TYPE timestamp(0) WITHOUT TIME ZONE USING birthdate::timestamp(0) WITHOUT TIME ZONE');

        $this->addSql('ALTER TABLE "user" ALTER company TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN "user".created IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP created');
        $this->addSql('ALTER TABLE "user" ALTER birthdate TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE "user" ALTER company TYPE VARCHAR(50)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d6491677722f ON "user" (avatar)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649d4e6f81 ON "user" (address)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d6494fbf094f ON "user" (company)');
    }
}
