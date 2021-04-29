<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191013175239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stay CHANGE bill_id bill_id INT DEFAULT NULL, CHANGE entry_id entry_id INT DEFAULT NULL, CHANGE spot spot VARCHAR(2) DEFAULT NULL, CHANGE registration registration VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE entry CHANGE family_id family_id INT DEFAULT NULL, CHANGE home_phone home_phone VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE deadgarage CHANGE stay_id stay_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE entry_id entry_id INT DEFAULT NULL, CHANGE bill_id bill_id INT DEFAULT NULL, CHANGE washing_token washing_token INT DEFAULT NULL, CHANGE drying_token drying_token INT DEFAULT NULL, CHANGE external_shower external_shower INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deadgarage CHANGE stay_id stay_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entry CHANGE family_id family_id INT DEFAULT NULL, CHANGE home_phone home_phone VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE phone phone VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE service CHANGE bill_id bill_id INT DEFAULT NULL, CHANGE entry_id entry_id INT DEFAULT NULL, CHANGE washing_token washing_token INT DEFAULT NULL, CHANGE drying_token drying_token INT DEFAULT NULL, CHANGE external_shower external_shower INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stay CHANGE entry_id entry_id INT DEFAULT NULL, CHANGE bill_id bill_id INT DEFAULT NULL, CHANGE spot spot VARCHAR(2) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE registration registration VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
