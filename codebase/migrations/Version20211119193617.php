<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211119193617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotional_code_product_type (promotional_code_id INT NOT NULL, product_type_id INT NOT NULL, INDEX IDX_867A93316DA3C5BB (promotional_code_id), INDEX IDX_867A933114959723 (product_type_id), PRIMARY KEY(promotional_code_id, product_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotional_code_product_type ADD CONSTRAINT FK_867A93316DA3C5BB FOREIGN KEY (promotional_code_id) REFERENCES promotional_code (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotional_code_product_type ADD CONSTRAINT FK_867A933114959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promotional_code_product_type');
    }
}
