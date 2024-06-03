<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603185413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_event_outlet (user_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_2175578BA76ED395 (user_id), INDEX IDX_2175578B71F7E88B (event_id), PRIMARY KEY(user_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_event_outlet ADD CONSTRAINT FK_2175578BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_event_outlet ADD CONSTRAINT FK_2175578B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_event_outlet DROP FOREIGN KEY FK_2175578BA76ED395');
        $this->addSql('ALTER TABLE user_event_outlet DROP FOREIGN KEY FK_2175578B71F7E88B');
        $this->addSql('DROP TABLE user_event_outlet');
    }
}
