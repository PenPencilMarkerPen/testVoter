<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022080315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE file ADD file_size VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE file DROP file_size');
    }
}
