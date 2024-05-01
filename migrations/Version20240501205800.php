<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501205800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_event (id INT AUTO_INCREMENT NOT NULL, event_comment_event_id INT NOT NULL, user_comment_event_id INT DEFAULT NULL, content LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rgpd TINYINT(1) NOT NULL, INDEX IDX_923492564AA36845 (event_comment_event_id), INDEX IDX_9234925611A6C950 (user_comment_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_event ADD CONSTRAINT FK_923492564AA36845 FOREIGN KEY (event_comment_event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE comment_event ADD CONSTRAINT FK_9234925611A6C950 FOREIGN KEY (user_comment_event_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_event DROP FOREIGN KEY FK_923492564AA36845');
        $this->addSql('ALTER TABLE comment_event DROP FOREIGN KEY FK_9234925611A6C950');
        $this->addSql('DROP TABLE comment_event');
    }
}
