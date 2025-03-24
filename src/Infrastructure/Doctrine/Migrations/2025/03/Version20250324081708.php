<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324081708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_profile (id VARCHAR(255) NOT NULL, event VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN user_profile.id IS \'(DC2Type:user_profile)\'');
        $this->addSql('COMMENT ON COLUMN user_profile.event IS \'(DC2Type:user_profile_event)\'');
        $this->addSql('CREATE TABLE users_profile_event (id VARCHAR(255) NOT NULL, profile VARCHAR(255) NOT NULL, username VARCHAR(32) NOT NULL, gender VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN users_profile_event.id IS \'(DC2Type:user_profile_event)\'');
        $this->addSql('COMMENT ON COLUMN users_profile_event.profile IS \'(DC2Type:user_profile)\'');
        $this->addSql('COMMENT ON COLUMN users_profile_event.birthday IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE users_profile_event');
    }
}
