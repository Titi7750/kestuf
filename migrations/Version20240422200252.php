<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422200252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE special_regime (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialRegime_event (special_regime_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_4EE1E0965B0C6EB8 (special_regime_id), INDEX IDX_4EE1E09671F7E88B (event_id), PRIMARY KEY(special_regime_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE specialRegime_event ADD CONSTRAINT FK_4EE1E0965B0C6EB8 FOREIGN KEY (special_regime_id) REFERENCES special_regime (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialRegime_event ADD CONSTRAINT FK_4EE1E09671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialRegime_event DROP FOREIGN KEY FK_4EE1E0965B0C6EB8');
        $this->addSql('ALTER TABLE specialRegime_event DROP FOREIGN KEY FK_4EE1E09671F7E88B');
        $this->addSql('DROP TABLE special_regime');
        $this->addSql('DROP TABLE specialRegime_event');
    }
}
