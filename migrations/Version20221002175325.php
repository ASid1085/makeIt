<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221002175325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4B03A8386');
        $this->addSql('DROP INDEX IDX_5C93B3A4B03A8386 ON projects');
        $this->addSql('ALTER TABLE projects CHANGE created_by_id creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A461220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A461220EA6 ON projects (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A461220EA6');
        $this->addSql('DROP INDEX IDX_5C93B3A461220EA6 ON projects');
        $this->addSql('ALTER TABLE projects CHANGE creator_id created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5C93B3A4B03A8386 ON projects (created_by_id)');
    }
}
