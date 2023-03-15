<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314084921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ac DROP FOREIGN KEY FK_E98478FB29F88205');
        $this->addSql('CREATE TABLE projets_ac (projets_id INT NOT NULL, ac_id INT NOT NULL, INDEX IDX_FC7731C597A6CB7 (projets_id), INDEX IDX_FC7731CD2E3ED2F (ac_id), PRIMARY KEY(projets_id, ac_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projets_ac ADD CONSTRAINT FK_FC7731C597A6CB7 FOREIGN KEY (projets_id) REFERENCES projets (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projets_ac ADD CONSTRAINT FK_FC7731CD2E3ED2F FOREIGN KEY (ac_id) REFERENCES ac (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lier DROP FOREIGN KEY FK_B133E8FA80F43E55');
        $this->addSql('DROP TABLE lier');
        $this->addSql('DROP INDEX IDX_E98478FB29F88205 ON ac');
        $this->addSql('ALTER TABLE ac DROP id_lier_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lier (id INT AUTO_INCREMENT NOT NULL, id_projet_id INT DEFAULT NULL, INDEX IDX_B133E8FA80F43E55 (id_projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lier ADD CONSTRAINT FK_B133E8FA80F43E55 FOREIGN KEY (id_projet_id) REFERENCES projets (id)');
        $this->addSql('ALTER TABLE projets_ac DROP FOREIGN KEY FK_FC7731C597A6CB7');
        $this->addSql('ALTER TABLE projets_ac DROP FOREIGN KEY FK_FC7731CD2E3ED2F');
        $this->addSql('DROP TABLE projets_ac');
        $this->addSql('ALTER TABLE ac ADD id_lier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ac ADD CONSTRAINT FK_E98478FB29F88205 FOREIGN KEY (id_lier_id) REFERENCES lier (id)');
        $this->addSql('CREATE INDEX IDX_E98478FB29F88205 ON ac (id_lier_id)');
    }
}
