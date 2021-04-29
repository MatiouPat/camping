<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191102163445 extends AbstractMigration
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
        $this->addSql('CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, surname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stay CHANGE spot spot VARCHAR(2) DEFAULT NULL, CHANGE registration registration VARCHAR(255) DEFAULT NULL, CHANGE wood_caravan wooden_caravan INT NOT NULL');
        $this->addSql('ALTER TABLE service CHANGE washing_token washing_token INT DEFAULT NULL, CHANGE drying_token drying_token INT DEFAULT NULL, CHANGE external_shower external_shower INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entry ADD family_id INT DEFAULT NULL, ADD surname VARCHAR(255) NOT NULL, ADD gender INT NOT NULL');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D70C35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('CREATE INDEX IDX_2B219D70C35E566A ON entry (family_id)');
        $this->addSql('ALTER TABLE bill CHANGE number number VARCHAR(255) NOT NULL, CHANGE paid_at paided_at DATE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D70C35E566A');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE family');
        $this->addSql('ALTER TABLE bill CHANGE number number INT NOT NULL, CHANGE paided_at paid_at DATE NOT NULL');
        $this->addSql('DROP INDEX IDX_2B219D70C35E566A ON entry');
        $this->addSql('ALTER TABLE entry DROP family_id, DROP surname, DROP gender');
        $this->addSql('ALTER TABLE service CHANGE washing_token washing_token INT NOT NULL, CHANGE drying_token drying_token INT NOT NULL, CHANGE external_shower external_shower INT NOT NULL');
        $this->addSql('ALTER TABLE stay CHANGE spot spot VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE registration registration VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE wooden_caravan wood_caravan INT NOT NULL');
    }
}
