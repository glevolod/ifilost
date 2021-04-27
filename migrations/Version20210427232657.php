<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427232657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE confirmation_queue (id INT AUTO_INCREMENT NOT NULL, tick_id INT NOT NULL, schedule_id INT NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, send_date_time DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E0956CCF1051469 (tick_id), INDEX IDX_E0956CCFA40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE confirmation_queue ADD CONSTRAINT FK_E0956CCF1051469 FOREIGN KEY (tick_id) REFERENCES tick (id)');
        $this->addSql('ALTER TABLE confirmation_queue ADD CONSTRAINT FK_E0956CCFA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('DROP TABLE tick_queue');
        $this->addSql('ALTER TABLE confirmation DROP FOREIGN KEY FK_483D123CA40BC2D5');
        $this->addSql('DROP INDEX UNIQ_483D123CA40BC2D5 ON confirmation');
        $this->addSql('ALTER TABLE confirmation ADD max_date_time DATETIME NOT NULL, CHANGE schedule_id queue_id INT NOT NULL');
        $this->addSql('ALTER TABLE confirmation ADD CONSTRAINT FK_483D123C477B5BAE FOREIGN KEY (queue_id) REFERENCES confirmation_queue (id)');
        $this->addSql('CREATE INDEX IDX_483D123C477B5BAE ON confirmation (queue_id)');
        $this->addSql('ALTER TABLE schedule ADD tick_id INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB1051469 FOREIGN KEY (tick_id) REFERENCES tick (id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB1051469 ON schedule (tick_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE confirmation DROP FOREIGN KEY FK_483D123C477B5BAE');
        $this->addSql('CREATE TABLE tick_queue (id INT AUTO_INCREMENT NOT NULL, tick_id INT NOT NULL, start_date_time DATETIME NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7F3AF7701051469 (tick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tick_queue ADD CONSTRAINT FK_7F3AF7701051469 FOREIGN KEY (tick_id) REFERENCES tick (id)');
        $this->addSql('DROP TABLE confirmation_queue');
        $this->addSql('DROP INDEX IDX_483D123C477B5BAE ON confirmation');
        $this->addSql('ALTER TABLE confirmation DROP max_date_time, CHANGE queue_id schedule_id INT NOT NULL');
        $this->addSql('ALTER TABLE confirmation ADD CONSTRAINT FK_483D123CA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_483D123CA40BC2D5 ON confirmation (schedule_id)');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB1051469');
        $this->addSql('DROP INDEX IDX_5A3811FB1051469 ON schedule');
        $this->addSql('ALTER TABLE schedule DROP tick_id');
    }
}
