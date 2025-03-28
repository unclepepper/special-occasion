<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328023103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_profile_info (id VARCHAR(255) NOT NULL, event VARCHAR(255) DEFAULT NULL, name VARCHAR(32) NOT NULL, surname VARCHAR(32) NOT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E0598CF3BAE0AA7 ON users_profile_info (event)');
        $this->addSql('COMMENT ON COLUMN users_profile_info.id IS \'(DC2Type:user_profile_info)\'');
        $this->addSql('COMMENT ON COLUMN users_profile_info.event IS \'(DC2Type:user_profile_event)\'');
        $this->addSql('ALTER TABLE users_profile_info ADD CONSTRAINT FK_2E0598CF3BAE0AA7 FOREIGN KEY (event) REFERENCES users_profile_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_profile_event ADD info_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users_profile_event.info_id IS \'(DC2Type:user_profile_info)\'');
        $this->addSql('ALTER TABLE users_profile_event ADD CONSTRAINT FK_C59F9D785D8BC1F8 FOREIGN KEY (info_id) REFERENCES users_profile_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C59F9D785D8BC1F8 ON users_profile_event (info_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users_profile_event DROP CONSTRAINT FK_C59F9D785D8BC1F8');
        $this->addSql('ALTER TABLE users_profile_info DROP CONSTRAINT FK_2E0598CF3BAE0AA7');
        $this->addSql('DROP TABLE users_profile_info');
        $this->addSql('DROP INDEX UNIQ_C59F9D785D8BC1F8');
        $this->addSql('ALTER TABLE users_profile_event DROP info_id');
    }
}
