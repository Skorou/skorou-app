<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201218155214 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE template_creation_category DROP FOREIGN KEY FK_2D326A0152191D37');
        $this->addSql('DROP TABLE creation_category');
        $this->addSql('DROP TABLE template_creation_category');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE creation_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, order_index INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE template_creation_category (template_id INT NOT NULL, creation_category_id INT NOT NULL, INDEX IDX_2D326A0152191D37 (creation_category_id), INDEX IDX_2D326A015DA0FB8 (template_id), PRIMARY KEY(template_id, creation_category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE template_creation_category ADD CONSTRAINT FK_2D326A0152191D37 FOREIGN KEY (creation_category_id) REFERENCES creation_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_creation_category ADD CONSTRAINT FK_2D326A015DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
    }
}
