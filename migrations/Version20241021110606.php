<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021110606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id SERIAL NOT NULL, users_id INT DEFAULT NULL, name VARCHAR(65) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1C52F95867B3B43D ON brand (users_id)');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F95867B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE brand DROP CONSTRAINT FK_1C52F95867B3B43D');
        $this->addSql('DROP TABLE brand');
    }
}
