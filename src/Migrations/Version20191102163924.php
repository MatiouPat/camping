<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191102163924 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stay (id INT AUTO_INCREMENT NOT NULL, entry_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, arrived_at DATE NOT NULL, leaved_at DATE NOT NULL, spot VARCHAR(2) DEFAULT NULL, adult INT NOT NULL, teenager INT NOT NULL, child INT NOT NULL, car INT NOT NULL, motor_bike INT NOT NULL, bike INT NOT NULL, camping_car INT NOT NULL, caravan INT NOT NULL, tent INT NOT NULL, wooden_caravan INT NOT NULL, registration VARCHAR(255) DEFAULT NULL, electricity TINYINT(1) NOT NULL, pet INT NOT NULL, booking TINYINT(1) NOT NULL, INDEX IDX_5E09839CBA364942 (entry_id), INDEX IDX_5E09839C1A8C12F5 (bill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, bill_id INT DEFAULT NULL, entry_id INT DEFAULT NULL, gived_at DATE NOT NULL, washing_token INT DEFAULT NULL, drying_token INT DEFAULT NULL, external_shower INT DEFAULT NULL, INDEX IDX_E19D9AD21A8C12F5 (bill_id), INDEX IDX_E19D9AD2BA364942 (entry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, surname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deadgarage (id INT AUTO_INCREMENT NOT NULL, stay_id INT DEFAULT NULL, started_at DATE NOT NULL, stopped_at DATE NOT NULL, INDEX IDX_602E00ECFB3AF7D6 (stay_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entry (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, gender INT NOT NULL, address VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, home_phone VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, INDEX IDX_2B219D70C35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bill (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, way INT NOT NULL, paided_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stay ADD CONSTRAINT FK_5E09839CBA364942 FOREIGN KEY (entry_id) REFERENCES entry (id)');
        $this->addSql('ALTER TABLE stay ADD CONSTRAINT FK_5E09839C1A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD21A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BA364942 FOREIGN KEY (entry_id) REFERENCES entry (id)');
        $this->addSql('ALTER TABLE deadgarage ADD CONSTRAINT FK_602E00ECFB3AF7D6 FOREIGN KEY (stay_id) REFERENCES stay (id)');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D70C35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deadgarage DROP FOREIGN KEY FK_602E00ECFB3AF7D6');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D70C35E566A');
        $this->addSql('ALTER TABLE stay DROP FOREIGN KEY FK_5E09839CBA364942');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BA364942');
        $this->addSql('ALTER TABLE stay DROP FOREIGN KEY FK_5E09839C1A8C12F5');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD21A8C12F5');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE stay');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE deadgarage');
        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE bill');
    }
}
