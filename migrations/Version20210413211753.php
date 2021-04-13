<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413211753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE confirmation (id INT AUTO_INCREMENT NOT NULL, schedule_id INT NOT NULL, attempts SMALLINT DEFAULT 0 NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, type SMALLINT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_483D123CA40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tick_queue (id INT AUTO_INCREMENT NOT NULL, tick_id INT NOT NULL, start_date_time DATETIME NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7F3AF7701051469 (tick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE confirmation ADD CONSTRAINT FK_483D123CA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE tick_queue ADD CONSTRAINT FK_7F3AF7701051469 FOREIGN KEY (tick_id) REFERENCES tick (id)');
        $this->addSql('ALTER TABLE schedule ADD type SMALLINT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tick ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE confirmation');
        $this->addSql('DROP TABLE tick_queue');
        $this->addSql('ALTER TABLE schedule DROP type, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE tick DROP created_at, DROP updated_at');
    }
}
