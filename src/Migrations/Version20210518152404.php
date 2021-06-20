<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518152404 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D37048FD0F');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3F5B7AF75');
        $this->addSql('DROP INDEX IDX_A3C664D3F5B7AF75 ON subscription');
        $this->addSql('DROP INDEX IDX_A3C664D37048FD0F ON subscription');
        $this->addSql('ALTER TABLE subscription DROP address_id, DROP credit_card_id, CHANGE payment_type payment_type SMALLINT DEFAULT NULL, CHANGE invoice invoice VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subscription ADD address_id INT DEFAULT NULL, ADD credit_card_id INT DEFAULT NULL, CHANGE payment_type payment_type SMALLINT NOT NULL, CHANGE invoice invoice VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D37048FD0F FOREIGN KEY (credit_card_id) REFERENCES credit_card (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_A3C664D3F5B7AF75 ON subscription (address_id)');
        $this->addSql('CREATE INDEX IDX_A3C664D37048FD0F ON subscription (credit_card_id)');
    }
}
