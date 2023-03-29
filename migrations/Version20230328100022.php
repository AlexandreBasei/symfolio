<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328100022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961A80F43E55');
        $this->addSql('DROP INDEX IDX_761C961A80F43E55 ON noter');
        $this->addSql('ALTER TABLE noter CHANGE id_projet_id projet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961AC18272 FOREIGN KEY (projet_id) REFERENCES projets (id)');
        $this->addSql('CREATE INDEX IDX_761C961AC18272 ON noter (projet_id)');
        $this->addSql('ALTER TABLE projets CHANGE tag tag VARCHAR(500) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961AC18272');
        $this->addSql('DROP INDEX IDX_761C961AC18272 ON noter');
        $this->addSql('ALTER TABLE noter CHANGE projet_id id_projet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961A80F43E55 FOREIGN KEY (id_projet_id) REFERENCES projets (id)');
        $this->addSql('CREATE INDEX IDX_761C961A80F43E55 ON noter (id_projet_id)');
        $this->addSql('ALTER TABLE projets CHANGE tag tag LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
