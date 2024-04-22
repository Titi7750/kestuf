<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422193521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ambiance (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ambiance_event (ambiance_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_4B7C416837A05A93 (ambiance_id), INDEX IDX_4B7C416871F7E88B (event_id), PRIMARY KEY(ambiance_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ambiance_event ADD CONSTRAINT FK_4B7C416837A05A93 FOREIGN KEY (ambiance_id) REFERENCES ambiance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ambiance_event ADD CONSTRAINT FK_4B7C416871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambiance_event DROP FOREIGN KEY FK_4B7C416837A05A93');
        $this->addSql('ALTER TABLE ambiance_event DROP FOREIGN KEY FK_4B7C416871F7E88B');
        $this->addSql('DROP TABLE ambiance');
        $this->addSql('DROP TABLE ambiance_event');
    }
}
