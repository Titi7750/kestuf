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
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_user ADD user_send_comment_id INT DEFAULT NULL, ADD user_receive_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5F5B483E7 FOREIGN KEY (user_send_comment_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A599565B4D FOREIGN KEY (user_receive_comment_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_ABA574A5F5B483E7 ON comment_user (user_send_comment_id)');
        $this->addSql('CREATE INDEX IDX_ABA574A599565B4D ON comment_user (user_receive_comment_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64999565B4D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B483E7');
        $this->addSql('DROP INDEX IDX_8D93D649F5B483E7 ON user');
        $this->addSql('DROP INDEX IDX_8D93D64999565B4D ON user');
        $this->addSql('ALTER TABLE user DROP user_send_comment_id, DROP user_receive_comment_id');
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
