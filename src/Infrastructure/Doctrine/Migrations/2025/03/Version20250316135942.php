<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316135942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create user table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "user" (usr VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(usr))');
        $this->addSql('COMMENT ON COLUMN "user".usr IS \'(DC2Type:user_id)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "user"');
    }
}
