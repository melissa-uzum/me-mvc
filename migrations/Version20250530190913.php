<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530190913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE book ADD title VARCHAR(255) NOT NULL, ADD isbn VARCHAR(13) NOT NULL, ADD author VARCHAR(255) NOT NULL, ADD image VARCHAR(255) NOT NULL, ADD language VARCHAR(50) NOT NULL, ADD pages INT NOT NULL, ADD publishedAt DATE NOT NULL, ADD publisher VARCHAR(100) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE Book DROP title, DROP isbn, DROP author, DROP image, DROP language, DROP pages, DROP publishedAt, DROP publisher
        SQL);
    }
}
