<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325022431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE especialidade (id INT AUTO_INCREMENT NOT NULL, descricao VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicos (id INT AUTO_INCREMENT NOT NULL, especialidade_id INT NOT NULL, crm INT NOT NULL, nome VARCHAR(255) DEFAULT NULL, INDEX IDX_645027213BA9BFA5 (especialidade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medicos ADD CONSTRAINT FK_645027213BA9BFA5 FOREIGN KEY (especialidade_id) REFERENCES especialidade (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicos DROP FOREIGN KEY FK_645027213BA9BFA5');
        $this->addSql('DROP TABLE especialidade');
        $this->addSql('DROP TABLE medicos');
    }
}
