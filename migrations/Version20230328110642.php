<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328110642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE noter (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, projet_id INT DEFAULT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, INDEX IDX_761C961A79F37AE5 (id_user_id), INDEX IDX_761C961AC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961A79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961AC18272 FOREIGN KEY (projet_id) REFERENCES projets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961A79F37AE5');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961AC18272');
        $this->addSql('DROP TABLE noter');
    }
}
