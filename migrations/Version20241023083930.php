<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023083930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE brand ADD date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN brand.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE file ADD date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN file.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE product ADD date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN product.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE token ADD date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN token.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" ADD date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN "user".date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE token DROP date');
        $this->addSql('ALTER TABLE brand DROP date');
        $this->addSql('ALTER TABLE "user" DROP date');
        $this->addSql('ALTER TABLE file DROP date');
        $this->addSql('ALTER TABLE product DROP date');
    }
}
