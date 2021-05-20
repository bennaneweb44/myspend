<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520114347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimentation ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE alimentation ADD CONSTRAINT FK_8E65DFA09D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8E65DFA09D86650F ON alimentation (user_id)');
        $this->addSql('ALTER TABLE charges ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE charges ADD CONSTRAINT FK_3AEF501A9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3AEF501A9D86650F ON charges (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimentation DROP FOREIGN KEY FK_8E65DFA09D86650F');
        $this->addSql('DROP INDEX IDX_8E65DFA09D86650F ON alimentation');
        $this->addSql('ALTER TABLE alimentation DROP user_id');
        $this->addSql('ALTER TABLE charges DROP FOREIGN KEY FK_3AEF501A9D86650F');
        $this->addSql('DROP INDEX IDX_3AEF501A9D86650F ON charges');
        $this->addSql('ALTER TABLE charges DROP user_id');
    }
}
