<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028095330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(product_id, category_id))');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04ad12469de2');
        $this->addSql('DROP INDEX idx_d34a04ad12469de2');
        $this->addSql('ALTER TABLE product DROP category_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product_category DROP CONSTRAINT FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP CONSTRAINT FK_CDFC735612469DE2');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('ALTER TABLE product ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04ad12469de2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d34a04ad12469de2 ON product (category_id)');
    }
}
