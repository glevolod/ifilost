<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210428214054 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE confirmation (id INT AUTO_INCREMENT NOT NULL, queue_id INT NOT NULL, attempts SMALLINT DEFAULT 0 NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, type SMALLINT DEFAULT 0 NOT NULL, max_date_time DATETIME NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_483D123C2B6FCFB2 (guid), INDEX IDX_483D123C477B5BAE (queue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE confirmation_queue (id INT AUTO_INCREMENT NOT NULL, tick_id INT NOT NULL, schedule_id INT NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, send_date_time DATETIME NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E0956CCF2B6FCFB2 (guid), INDEX IDX_E0956CCF1051469 (tick_id), INDEX IDX_E0956CCFA40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifiable (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, email VARCHAR(255) NOT NULL, email_confirmed_at DATETIME DEFAULT NULL, text LONGTEXT NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EF9C43B32B6FCFB2 (guid), INDEX IDX_EF9C43B3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tick_id INT NOT NULL, date DATE DEFAULT NULL, time TIME NOT NULL, frequency SMALLINT DEFAULT NULL, exceptions JSON DEFAULT NULL, timeout SMALLINT NOT NULL, reminder_timeout SMALLINT DEFAULT NULL, type SMALLINT NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5A3811FB2B6FCFB2 (guid), INDEX IDX_5A3811FBA76ED395 (user_id), INDEX IDX_5A3811FB1051469 (tick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tick (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, fail_sign VARCHAR(255) DEFAULT NULL, sign VARCHAR(255) NOT NULL, prompt VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, email_confirmed_at DATETIME DEFAULT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_16AF98CC2B6FCFB2 (guid), UNIQUE INDEX UNIQ_16AF98CCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6492B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE confirmation ADD CONSTRAINT FK_483D123C477B5BAE FOREIGN KEY (queue_id) REFERENCES confirmation_queue (id)');
        $this->addSql('ALTER TABLE confirmation_queue ADD CONSTRAINT FK_E0956CCF1051469 FOREIGN KEY (tick_id) REFERENCES tick (id)');
        $this->addSql('ALTER TABLE confirmation_queue ADD CONSTRAINT FK_E0956CCFA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE notifiable ADD CONSTRAINT FK_EF9C43B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB1051469 FOREIGN KEY (tick_id) REFERENCES tick (id)');
        $this->addSql('ALTER TABLE tick ADD CONSTRAINT FK_16AF98CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE confirmation DROP FOREIGN KEY FK_483D123C477B5BAE');
        $this->addSql('ALTER TABLE confirmation_queue DROP FOREIGN KEY FK_E0956CCFA40BC2D5');
        $this->addSql('ALTER TABLE confirmation_queue DROP FOREIGN KEY FK_E0956CCF1051469');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB1051469');
        $this->addSql('ALTER TABLE notifiable DROP FOREIGN KEY FK_EF9C43B3A76ED395');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBA76ED395');
        $this->addSql('ALTER TABLE tick DROP FOREIGN KEY FK_16AF98CCA76ED395');
        $this->addSql('DROP TABLE confirmation');
        $this->addSql('DROP TABLE confirmation_queue');
        $this->addSql('DROP TABLE notifiable');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE tick');
        $this->addSql('DROP TABLE user');
    }
}
