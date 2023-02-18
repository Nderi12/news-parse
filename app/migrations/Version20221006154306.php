<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006154306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD date_added DATETIME NOT NULL, ADD last_updated DATETIME NOT NULL, DROP url, DROP date_time, DROP author');
        $this->addSql('ALTER TABLE source_url DROP name, DROP author_selector, DROP link_selector, DROP date_selector');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD url VARCHAR(255) NOT NULL, ADD date_time VARCHAR(255) NOT NULL, ADD author VARCHAR(255) NOT NULL, DROP date_added, DROP last_updated');
        $this->addSql('ALTER TABLE source_url ADD name VARCHAR(255) NOT NULL, ADD author_selector VARCHAR(255) NOT NULL, ADD link_selector VARCHAR(255) NOT NULL, ADD date_selector VARCHAR(255) NOT NULL');
    }
}
