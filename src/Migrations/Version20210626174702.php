<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626174702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, address1 VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, is_favorite TINYINT(1) DEFAULT NULL, INDEX IDX_D4E6F81A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, hexa VARCHAR(7) NOT NULL, INDEX IDX_665648E9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, folder_id INT DEFAULT NULL, creation_type_id INT DEFAULT NULL, name VARCHAR(256) NOT NULL, data JSON NOT NULL, INDEX IDX_57EE8574A76ED395 (user_id), INDEX IDX_57EE8574162CB942 (folder_id), INDEX IDX_57EE8574E54D5EF8 (creation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creation_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, order_index INT NOT NULL, height INT NOT NULL, width INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit_card (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, number VARCHAR(30) NOT NULL, expiry_month INT NOT NULL, expiry_year INT NOT NULL, cvv INT NOT NULL, is_favorite TINYINT(1) NOT NULL, INDEX IDX_11D627EEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_ECA209CD727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE font (id INT AUTO_INCREMENT NOT NULL, file VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE font_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, font_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9D1577B7A76ED395 (user_id), INDEX IDX_9D1577B7D7F7F9EB (font_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, file VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_uploaded (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_184FF89AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logo (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E48E9A13A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, payment_type SMALLINT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, invoice VARCHAR(255) DEFAULT NULL, INDEX IDX_A3C664D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, creation_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, order_index INT NOT NULL, data JSON NOT NULL, INDEX IDX_97601F83E54D5EF8 (creation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_form_field (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, label VARCHAR(255) NOT NULL, placeholder VARCHAR(255) NOT NULL, options LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', default_value VARCHAR(255) NOT NULL, INDEX IDX_FA79A4545DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, company_name VARCHAR(50) NOT NULL, company_picture VARCHAR(255) DEFAULT NULL, free_creations INT NOT NULL, is_active TINYINT(1) DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, roles JSON NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, fax_number VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, company_status VARCHAR(255) DEFAULT NULL, capital INT DEFAULT NULL, ape_code VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, siren VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE color ADD CONSTRAINT FK_665648E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE creation ADD CONSTRAINT FK_57EE8574A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE creation ADD CONSTRAINT FK_57EE8574162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE creation ADD CONSTRAINT FK_57EE8574E54D5EF8 FOREIGN KEY (creation_type_id) REFERENCES creation_type (id)');
        $this->addSql('ALTER TABLE credit_card ADD CONSTRAINT FK_11D627EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CD727ACA70 FOREIGN KEY (parent_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE font_user ADD CONSTRAINT FK_9D1577B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE font_user ADD CONSTRAINT FK_9D1577B7D7F7F9EB FOREIGN KEY (font_id) REFERENCES font (id)');
        $this->addSql('ALTER TABLE image_uploaded ADD CONSTRAINT FK_184FF89AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE logo ADD CONSTRAINT FK_E48E9A13A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F83E54D5EF8 FOREIGN KEY (creation_type_id) REFERENCES creation_type (id)');
        $this->addSql('ALTER TABLE template_form_field ADD CONSTRAINT FK_FA79A4545DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE creation DROP FOREIGN KEY FK_57EE8574E54D5EF8');
        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F83E54D5EF8');
        $this->addSql('ALTER TABLE creation DROP FOREIGN KEY FK_57EE8574162CB942');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD727ACA70');
        $this->addSql('ALTER TABLE font_user DROP FOREIGN KEY FK_9D1577B7D7F7F9EB');
        $this->addSql('ALTER TABLE template_form_field DROP FOREIGN KEY FK_FA79A4545DA0FB8');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE color DROP FOREIGN KEY FK_665648E9A76ED395');
        $this->addSql('ALTER TABLE creation DROP FOREIGN KEY FK_57EE8574A76ED395');
        $this->addSql('ALTER TABLE credit_card DROP FOREIGN KEY FK_11D627EEA76ED395');
        $this->addSql('ALTER TABLE font_user DROP FOREIGN KEY FK_9D1577B7A76ED395');
        $this->addSql('ALTER TABLE image_uploaded DROP FOREIGN KEY FK_184FF89AA76ED395');
        $this->addSql('ALTER TABLE logo DROP FOREIGN KEY FK_E48E9A13A76ED395');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE creation');
        $this->addSql('DROP TABLE creation_type');
        $this->addSql('DROP TABLE credit_card');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE font');
        $this->addSql('DROP TABLE font_user');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_uploaded');
        $this->addSql('DROP TABLE logo');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE template_form_field');
        $this->addSql('DROP TABLE user');
    }
}
