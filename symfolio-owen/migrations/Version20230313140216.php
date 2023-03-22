<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313140216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ac (id INT AUTO_INCREMENT NOT NULL, id_lier_id INT DEFAULT NULL, nom VARCHAR(150) NOT NULL, competence VARCHAR(50) NOT NULL, niveau INT NOT NULL, INDEX IDX_E98478FB29F88205 (id_lier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lier (id INT AUTO_INCREMENT NOT NULL, id_projet_id INT DEFAULT NULL, INDEX IDX_B133E8FA80F43E55 (id_projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noter (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_projet_id INT DEFAULT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, INDEX IDX_761C961A79F37AE5 (id_user_id), INDEX IDX_761C961A80F43E55 (id_projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projets (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, tag LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', date_publi DATE NOT NULL, INDEX IDX_B454C1DB79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ac ADD CONSTRAINT FK_E98478FB29F88205 FOREIGN KEY (id_lier_id) REFERENCES lier (id)');
        $this->addSql('ALTER TABLE lier ADD CONSTRAINT FK_B133E8FA80F43E55 FOREIGN KEY (id_projet_id) REFERENCES projets (id)');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961A79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961A80F43E55 FOREIGN KEY (id_projet_id) REFERENCES projets (id)');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE prof');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, iut VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_717E22E3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prof (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_5BBA70BBE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ac DROP FOREIGN KEY FK_E98478FB29F88205');
        $this->addSql('ALTER TABLE lier DROP FOREIGN KEY FK_B133E8FA80F43E55');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961A79F37AE5');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961A80F43E55');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB79F37AE5');
        $this->addSql('DROP TABLE ac');
        $this->addSql('DROP TABLE lier');
        $this->addSql('DROP TABLE noter');
        $this->addSql('DROP TABLE projets');
    }
}
