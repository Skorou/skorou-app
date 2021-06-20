<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518121806 extends AbstractMigration
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
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D37048FD0F');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3F5B7AF75');
        $this->addSql('DROP INDEX IDX_A3C664D3F5B7AF75 ON subscription');
        $this->addSql('DROP INDEX IDX_A3C664D37048FD0F ON subscription');
        $this->addSql('ALTER TABLE subscription DROP address_id, DROP credit_card_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE creation_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, order_index INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE template_creation_category (template_id INT NOT NULL, creation_category_id INT NOT NULL, INDEX IDX_2D326A015DA0FB8 (template_id), INDEX IDX_2D326A0152191D37 (creation_category_id), PRIMARY KEY(template_id, creation_category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE template_creation_category ADD CONSTRAINT FK_2D326A0152191D37 FOREIGN KEY (creation_category_id) REFERENCES creation_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_creation_category ADD CONSTRAINT FK_2D326A015DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription ADD address_id INT NOT NULL, ADD credit_card_id INT NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D37048FD0F FOREIGN KEY (credit_card_id) REFERENCES credit_card (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_A3C664D3F5B7AF75 ON subscription (address_id)');
        $this->addSql('CREATE INDEX IDX_A3C664D37048FD0F ON subscription (credit_card_id)');
    }
}
