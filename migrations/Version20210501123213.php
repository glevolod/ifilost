<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501123213 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, confirmation_id INT NOT NULL, type SMALLINT NOT NULL, status SMALLINT NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BF5476CA2B6FCFB2 (guid), UNIQUE INDEX UNIQ_BF5476CA6BACE54E (confirmation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_notifiable (notification_id INT NOT NULL, notifiable_id INT NOT NULL, INDEX IDX_14EEE691EF1A9D84 (notification_id), INDEX IDX_14EEE69183B4463F (notifiable_id), PRIMARY KEY(notification_id, notifiable_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA6BACE54E FOREIGN KEY (confirmation_id) REFERENCES confirmation (id)');
        $this->addSql('ALTER TABLE notification_notifiable ADD CONSTRAINT FK_14EEE691EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_notifiable ADD CONSTRAINT FK_14EEE69183B4463F FOREIGN KEY (notifiable_id) REFERENCES notifiable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE confirmation ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE confirmation ADD CONSTRAINT FK_483D123CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_483D123CA76ED395 ON confirmation (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_notifiable DROP FOREIGN KEY FK_14EEE691EF1A9D84');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_notifiable');
        $this->addSql('ALTER TABLE confirmation DROP FOREIGN KEY FK_483D123CA76ED395');
        $this->addSql('DROP INDEX IDX_483D123CA76ED395 ON confirmation');
        $this->addSql('ALTER TABLE confirmation DROP user_id');
    }
}
