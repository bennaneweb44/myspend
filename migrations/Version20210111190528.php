<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111190528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alimentation (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_8E65DFA0BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_alimentation (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, couleur VARCHAR(255) DEFAULT NULL, icone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_charge (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, couleur VARCHAR(255) DEFAULT NULL, icone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE charges (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_3AEF501ABCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametres (id INT AUTO_INCREMENT NOT NULL, clee VARCHAR(255) NOT NULL, valeur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alimentation ADD CONSTRAINT FK_8E65DFA0BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_alimentation (id)');
        $this->addSql('ALTER TABLE charges ADD CONSTRAINT FK_3AEF501ABCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_charge (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimentation DROP FOREIGN KEY FK_8E65DFA0BCF5E72D');
        $this->addSql('ALTER TABLE charges DROP FOREIGN KEY FK_3AEF501ABCF5E72D');
        $this->addSql('DROP TABLE alimentation');
        $this->addSql('DROP TABLE categorie_alimentation');
        $this->addSql('DROP TABLE categorie_charge');
        $this->addSql('DROP TABLE charges');
        $this->addSql('DROP TABLE parametres');
        $this->addSql('ALTER TABLE user CHANGE roles roles TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
