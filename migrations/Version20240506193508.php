<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506193508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Création de la table 'comment_user' avec toutes les colonnes nécessaires
        $this->addSql('CREATE TABLE comment_user (id INT AUTO_INCREMENT NOT NULL, user_send_comment_id INT DEFAULT NULL, user_receive_comment_id INT DEFAULT NULL, content LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rgpd TINYINT(1) NOT NULL, INDEX IDX_ABA574A5F5B483E7 (user_send_comment_id), INDEX IDX_ABA574A599565B4D (user_receive_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5F5B483E7 FOREIGN KEY (user_send_comment_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A599565B4D FOREIGN KEY (user_receive_comment_id) REFERENCES user (id)');

        // Vérifier si les clés étrangères existent avant de les supprimer
        $foreignKeyNames = $this->connection->createSchemaManager()->listTableForeignKeys('user'); // Récupération des clés étrangères de la table 'user'
        $foreignKeyNames = array_map(function ($fk) {
            return $fk->getName();
        }, $foreignKeyNames);

        if (in_array('FK_8D93D64999565B4D', $foreignKeyNames)) {
            $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64999565B4D');
        }
        if (in_array('FK_8D93D649F5B483E7', $foreignKeyNames)) {
            $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B483E7');
        }

        // Suppression des indices liés aux clés étrangères
        if ($schema->getTable('user')->hasIndex('IDX_8D93D649F5B483E7')) {
            $this->addSql('DROP INDEX IDX_8D93D649F5B483E7 ON user');
        }
        if ($schema->getTable('user')->hasIndex('IDX_8D93D64999565B4D')) {
            $this->addSql('DROP INDEX IDX_8D93D64999565B4D ON user');
        }

        $userTable = $schema->getTable('user');
        if ($userTable->hasColumn('user_send_comment_id')) {
            $this->addSql('ALTER TABLE user DROP user_send_comment_id');
        }
        if ($userTable->hasColumn('user_receive_comment_id')) {
            $this->addSql('ALTER TABLE user DROP user_receive_comment_id');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD user_send_comment_id INT DEFAULT NULL, ADD user_receive_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64999565B4D FOREIGN KEY (user_receive_comment_id) REFERENCES comment_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B483E7 FOREIGN KEY (user_send_comment_id) REFERENCES comment_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649F5B483E7 ON user (user_send_comment_id)');
        $this->addSql('CREATE INDEX IDX_8D93D64999565B4D ON user (user_receive_comment_id)');
        $this->addSql('ALTER TABLE comment_user DROP FOREIGN KEY FK_ABA574A5F5B483E7');
        $this->addSql('ALTER TABLE comment_user DROP FOREIGN KEY FK_ABA574A599565B4D');
        $this->addSql('DROP INDEX IDX_ABA574A5F5B483E7 ON comment_user');
        $this->addSql('DROP INDEX IDX_ABA574A599565B4D ON comment_user');
        $this->addSql('ALTER TABLE comment_user DROP user_send_comment_id, DROP user_receive_comment_id');
    }
}
