<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924151424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, focus_pays_id INT NOT NULL, author_id INT NOT NULL, focus_ville_id INT NOT NULL, focus_lieu_id INT NOT NULL, created_at DATETIME NOT NULL, rating INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526C5C528DDD (focus_pays_id), INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C2E169D19 (focus_ville_id), INDEX IDX_9474526C9004DC55 (focus_lieu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5C528DDD FOREIGN KEY (focus_pays_id) REFERENCES focus_pays (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C2E169D19 FOREIGN KEY (focus_ville_id) REFERENCES focus_ville (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9004DC55 FOREIGN KEY (focus_lieu_id) REFERENCES focus_lieu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment');
    }
}
